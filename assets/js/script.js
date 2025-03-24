jQuery(document).ready(function ($) {
    var province = $(".iran-eto-calculator-container").data("province");
    var city = $(".iran-eto-calculator-container").data("city");
    var cityFaName = $(".iran-eto-calculator-container").data("fa-name");
    var currentData = null;

    console.log("Initial province: " + province + ", city: " + city + ", fa-name: " + cityFaName);

    function getCookie(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
        return null;
    }

    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getSeenCities() {
        var seenCities = getCookie("iran_eto_seen_cities");
        return seenCities ? JSON.parse(seenCities) : [];
    }

    function addSeenCity(city) {
        var seenCities = getSeenCities();
        if (!seenCities.includes(city)) {
            seenCities.push(city);
            setCookie("iran_eto_seen_cities", JSON.stringify(seenCities), 1); // کوکی برای 1 روز
        }
        return seenCities.length;
    }

    function showToast(message) {
        Toastify({
            text: message,
            duration: 5000,
            close: true,
            gravity: "top",
            position: "center",
            backgroundColor: "#ff4444",
            stopOnFocus: true
        }).showToast();
    }

    function updateCities() {
        var selectedProvince = $("#iran-eto-province").val();
        console.log("Selected province: " + selectedProvince);
        $("#iran-eto-city").empty();
        var cities = iran_eto_params.provinces[selectedProvince];
        if (cities) {
            $.each(cities, function (cityName, coords) {
                var cityFa = iran_eto_params.city_names_fa[cityName] || cityName;
                $("#iran-eto-city").append(
                    $("<option>").val(cityName).text(cityFa)
                );
            });
            var firstCity = Object.keys(cities)[0];
            $("#iran-eto-city").val(firstCity);
            city = firstCity;
            cityFaName = iran_eto_params.city_names_fa[firstCity] || firstCity;
            console.log("Cities updated for " + selectedProvince + ", selected city: " + city);
        } else {
            console.log("No cities found for province: " + selectedProvince);
        }
    }

    function loadEtoData(city) {
        if (!city) {
            console.log("No city selected, skipping loadEtoData");
            return;
        }

        var seenCities = getSeenCities();
        if (!seenCities.includes(city)) {
            var cityCount = addSeenCity(city);
            if (cityCount > 2) {
                showToast("شما از حد مجاز بیشتر استفاده کردید! در هر روز فقط می‌توانید اطلاعات دو شهر را ببینید.");
                return;
            }
        }

        console.log("Loading ETo data for city: " + city);
        $(".iran-eto-loading").show();
        $(".iran-eto-chart-container, .iran-eto-data-table").hide();
        $("#iran-eto-export-excel").hide();

        $.ajax({
            url: iran_eto_params.rest_url + "forecast/" + city,
            method: "GET",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", iran_eto_params.nonce);
            },
            success: function (data) {
                console.log("Data received: ", data);
                $(".iran-eto-loading").hide();
                if (data && data.length > 0) {
                    currentData = data;
                    displayEtoData(data, cityFaName);
                    $("#iran-eto-export-excel").show();
                } else {
                    showToast("داده‌ای برای نمایش وجود ندارد!");
                    $(".iran-eto-data-table").html("<p style='color:red; text-align:center;'>داده‌ای برای نمایش وجود ندارد!</p>").show();
                }
            },
            error: function (xhr) {
                console.log("Error fetching data: ", xhr);
                $(".iran-eto-loading").hide();
                var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "خطا در دریافت داده‌ها.";
                $(".iran-eto-data-table").html("<p style='color:red; text-align:center;'>" + errorMsg + "</p>").show();
            }
        });
    }

    function displayEtoData(data, cityFaName) {
        console.log("Displaying data for: " + cityFaName);
        $("#iran-eto-data-body").empty();

        var title = "<h3>پیش‌بینی تبخیر و تعرق مرجع برای " + cityFaName + "</h3>";
        if ($(".iran-eto-data-table h3").length) {
            $(".iran-eto-data-table h3").text("پیش‌بینی تبخیر و تعرق مرجع برای " + cityFaName);
        } else {
            $(".iran-eto-data-table").prepend(title);
        }

        var dates = [];
        var etoValues = [];
        var tempValues = [];
        var tableBody = "";

        $.each(data, function (index, item) {
            var persianDate = item.persian_date || item.date;
            dates.push(persianDate);
            etoValues.push(item.eto);
            tempValues.push(item.temp_mean);

            tableBody += "<tr>" +
                "<td>" + persianDate + "</td>" +
                "<td>" + item.eto + "</td>" +
                "<td>" + item.temp_mean.toFixed(1) + "</td>" +
                "<td>" + item.humidity + "</td>" +
                "<td>" + item.wind_speed.toFixed(1) + "</td>" +
                "<td>" + item.solar_radiation.toFixed(1) + "</td>" +
                "</tr>";
        });

        $("#iran-eto-data-body").html(tableBody);

        var ctx = document.getElementById("iran-eto-chart").getContext("2d");
        if (window.etoChart instanceof Chart) window.etoChart.destroy();

        // تنظیمات ریسپانسیو برای دسکتاپ و موبایل
        var isMobile = window.innerWidth <= 768;
        window.etoChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: dates,
                datasets: [{
                    label: "تبخیر و تعرق (mm/day)",
                    data: etoValues,
                    borderColor: "rgba(54, 162, 235, 1)",
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    borderWidth: 2,
                    fill: true,
                    yAxisID: "y"
                }, {
                    label: "دمای میانگین (°C)",
                    data: tempValues,
                    borderColor: "rgba(255, 99, 132, 1)",
                    backgroundColor: "rgba(255, 99, 132, 0.2)",
                    borderWidth: 2,
                    fill: false,
                    yAxisID: "y1"
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: "نمودار تبخیر و تعرق و دما",
                        font: {
                            size: isMobile ? 14 : 18,
                            family: "'Anjoman', sans-serif"
                        },
                        color: "#333",
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    },
                    legend: {
                        position: "top",
                        labels: {
                            font: {
                                family: "'Anjoman', sans-serif",
                                size: isMobile ? 12 : 14
                            },
                            color: "#555",
                            padding: 15
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: isMobile ? 90 : 45, 
                            minRotation: isMobile ? 90 : 45,
                            font: {
                                family: "'Anjoman', sans-serif",
                                size: isMobile ? 10 : 12
                            },
                            color: "#555",
                            maxTicksLimit: isMobile ? 5 : 10 
                        },
                        grid: {
                            display: false 
                        }
                    },
                    y: {
                        position: "left",
                        title: {
                            display: true,
                            text: "تبخیر و تعرق (mm/day)",
                            font: {
                                family: "'Anjoman', sans-serif",
                                size: isMobile ? 12 : 14
                            },
                            color: "#555"
                        },
                        ticks: {
                            font: {
                                family: "'Anjoman', sans-serif",
                                size: isMobile ? 10 : 12
                            },
                            color: "#555"
                        }
                    },
                    y1: {
                        position: "right",
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: "دما (°C)",
                            font: {
                                family: "'Anjoman', sans-serif",
                                size: isMobile ? 12 : 14
                            },
                            color: "#555"
                        },
                        ticks: {
                            font: {
                                family: "'Anjoman', sans-serif",
                                size: isMobile ? 10 : 12
                            },
                            color: "#555"
                        }
                    }
                }
            }
        });

        $(".iran-eto-chart-container, .iran-eto-data-table").show();
    }

    function exportToExcel() {
        if (!currentData || currentData.length === 0) {
            showToast("داده‌ای برای خروجی وجود ندارد!");
            return;
        }

        var csv = "تاریخ,تبخیر و تعرق (mm),دمای میانگین (°C),رطوبت (%),سرعت باد (m/s),تابش خورشیدی (MJ/m²/day)\n";
        $.each(currentData, function (index, item) {
            var persianDate = item.persian_date || item.date;
            csv += [
                persianDate,
                item.eto.toFixed(2),
                item.temp_mean.toFixed(1),
                item.humidity.toFixed(1),
                item.wind_speed.toFixed(1),
                item.solar_radiation.toFixed(1)
            ].map(value => `"${value}"`).join(",") + "\n";
        });

        var blob = new Blob(["\ufeff" + csv], { type: "text/csv;charset=utf-8;" });
        var link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "eto_forecast_" + cityFaName + "_" + new Date().toISOString().split("T")[0] + ".csv";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    updateCities();

    $("#iran-eto-province").on("change", function () {
        province = $(this).val();
        updateCities();
    });

    $("#iran-eto-city").on("change", function () {
        city = $(this).val();
        cityFaName = iran_eto_params.city_names_fa[city] || city;
    });

    $("#iran-eto-load-data").on("click", function () {
        loadEtoData(city);
    });

    $("#iran-eto-export-excel").on("click", function () {
        exportToExcel();
    });
});