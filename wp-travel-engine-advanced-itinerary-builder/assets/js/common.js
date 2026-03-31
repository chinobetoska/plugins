var _wteAIL10n3, _ref, _wteAIL10n4, _wteAIL10n4$data, _wteAIL10n$data$color3, _wteAIL10n5, _wteAIL10n5$data, _wteAIL10n$alt_unit, _wteAIL10n6, _wteAIL10n$data$datas, _wteAIL10n7, _wteAIL10n$options$sc, _wteAIL10n8, _wteAIL10n9, _ref2, _wteAIL10n10, _wteAIL10n10$data, _wteAIL10n$options$sc2, _wteAIL10n11, _wteAIL10n12, _wteAIL10n13, _wteAIL10n$data$color4, _wteAIL10n14, _wteAIL10n14$data, _wteAIL10n$data$color5, _wteAIL10n15, _wteAIL10n15$data;

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

var wteAIChart = function wteAIChart() { };

wteAIChart.chart = null;
wteAIChart.chartData = {};
wteAIChart.datalabels = function (unit) {
  var _wteAIL10n$data$color, _wteAIL10n, _wteAIL10n$data, _wteAIL10n$data$color2, _wteAIL10n2, _wteAIL10n2$data;

  return {
    labels: {
      name: {
        // color: (_wteAIL10n$data$color = (_wteAIL10n = wteAIL10n) === null || _wteAIL10n === void 0 ? void 0 : (_wteAIL10n$data = _wteAIL10n.data) === null || _wteAIL10n$data === void 0 ? void 0 : _wteAIL10n$data.color) !== null && _wteAIL10n$data$color !== void 0 ? _wteAIL10n$data$color : "#147dfe",
        color: "#0F1D23",
        align: "top",
        offset: 28,
        font: {
          size: 14,
          lineHeight: 1.7
        },
        formatter: function formatter(value, ctx) {
          return Object.values(wteAIChart.chartData)[ctx.dataIndex].at;
        }
      },
      value: {
        align: "top",
        // color: (_wteAIL10n$data$color2 = (_wteAIL10n2 = wteAIL10n) === null || _wteAIL10n2 === void 0 ? void 0 : (_wteAIL10n2$data = _wteAIL10n2.data) === null || _wteAIL10n2$data === void 0 ? void 0 : _wteAIL10n2$data.color) !== null && _wteAIL10n$data$color2 !== void 0 ? _wteAIL10n$data$color2 : "#147dfe",
        color: "#0F1D23",
        offset: 4,
        font: {
          size: 14,
          weight: "bold",
          lineHeight: 1.7
        },
        padding: 4,
        formatter: function formatter(value, ctx) {
          return "".concat(value, " ").concat(unit, ".");
        }
      }
    }
  };
}, wteAIChart.settings = {
  type: "line",
  data: {
    datasets: [{
      tension: 0.3,
      label: (_wteAIL10n3 = wteAIL10n) === null || _wteAIL10n3 === void 0 ? void 0 : _wteAIL10n3.strings["data.datasets.label"],
      data: [],
      backgroundColor: [(_ref = ((_wteAIL10n4 = wteAIL10n) === null || _wteAIL10n4 === void 0 ? void 0 : (_wteAIL10n4$data = _wteAIL10n4.data) === null || _wteAIL10n4$data === void 0 ? void 0 : _wteAIL10n4$data.color) + "33") !== null && _ref !== void 0 ? _ref : "#147dfe33"],
      borderColor: [(_wteAIL10n$data$color3 = (_wteAIL10n5 = wteAIL10n) === null || _wteAIL10n5 === void 0 ? void 0 : (_wteAIL10n5$data = _wteAIL10n5.data) === null || _wteAIL10n5$data === void 0 ? void 0 : _wteAIL10n5$data.color) !== null && _wteAIL10n$data$color3 !== void 0 ? _wteAIL10n$data$color3 : "#147dfe"],
      borderWidth: 3,
      datalabels: wteAIChart.datalabels((_wteAIL10n$alt_unit = (_wteAIL10n6 = wteAIL10n) === null || _wteAIL10n6 === void 0 ? void 0 : _wteAIL10n6.alt_unit) !== null && _wteAIL10n$alt_unit !== void 0 ? _wteAIL10n$alt_unit : "m"),
      fill: (_wteAIL10n$data$datas = (_wteAIL10n7 = wteAIL10n) === null || _wteAIL10n7 === void 0 ? void 0 : _wteAIL10n7.data["datasets.data.fill"]) !== null && _wteAIL10n$data$datas !== void 0 ? _wteAIL10n$data$datas : true,

      // point
      pointRadius: 7,
      pointHoverRadius: 5,
      pointBackgroundColor: "#fff",
      pointBorderColor: _wteAIL10n4$data.color,
      pointBorderWidth: 2,
      pointHoverBorderWidth: 5,
      pointHoverBackgroundColor: "#fff",
      pointHoverBorderColor: _wteAIL10n4$data.color
    }],
    labels: []
  },
  options: {
    showAllTooltips: true,
    scales: {
      xAxes: [{
        display: (_wteAIL10n$options$sc = (_wteAIL10n8 = wteAIL10n) === null || _wteAIL10n8 === void 0 ? void 0 : _wteAIL10n8.options["scales.xAxes.display"]) !== null && _wteAIL10n$options$sc !== void 0 ? _wteAIL10n$options$sc : false,
        scaleLabel: {
          display: false,
          labelString: (_wteAIL10n9 = wteAIL10n) === null || _wteAIL10n9 === void 0 ? void 0 : _wteAIL10n9.strings["options.scales.xAxes.scaleLabel.labelString"]
        },
        ticks: {
          beginAtZero: true
        },
        gridLines: {
          // You can change the color, the dash effect, the main axe color, etc.
          borderDash: [8, 4],
          color: "rgba(15, 29, 35, 0.1)"
        }
      }],
      yAxes: [{
        display: (_wteAIL10n$options$sc2 = (_wteAIL10n11 = wteAIL10n) === null || _wteAIL10n11 === void 0 ? void 0 : _wteAIL10n11.options["scales.yAxes.display"]) !== null && _wteAIL10n$options$sc2 !== void 0 ? _wteAIL10n$options$sc2 : false,
        scaleLabel: {
          display: true,
          labelString: (_wteAIL10n12 = wteAIL10n) === null || _wteAIL10n12 === void 0 ? void 0 : _wteAIL10n12.strings["options.scales.yAxes.scaleLabel.labelString"]
        },
        ticks: {
          beginAtZero: true
        },
        gridLines: {
          color: "rgba(15, 29, 35, 0.1)",
        }
      }]
    },
    pointLabels: {
      display: true
    },
    title: {
      display: !1,
      text: (_wteAIL10n13 = wteAIL10n) === null || _wteAIL10n13 === void 0 ? void 0 : _wteAIL10n13.strings["options.title.text"]
    },
    elements: {
      point: {
        backgroundColor: (_wteAIL10n$data$color4 = (_wteAIL10n14 = wteAIL10n) === null || _wteAIL10n14 === void 0 ? void 0 : (_wteAIL10n14$data = _wteAIL10n14.data) === null || _wteAIL10n14$data === void 0 ? void 0 : _wteAIL10n14$data.color) !== null && _wteAIL10n$data$color4 !== void 0 ? _wteAIL10n$data$color4 : "#147dfe",
        borderWidth: 3
      },
    },
    layout: {
      padding: {
        left: 32,
        right: 32,
        top: 60,
        bottom: 32
      }
    },
    responsive: true,
    maintainAspectRatio: !1,
    // tension: 2,
    legend: {
      fontColor: (_wteAIL10n$data$color5 = (_wteAIL10n15 = wteAIL10n) === null || _wteAIL10n15 === void 0 ? void 0 : (_wteAIL10n15$data = _wteAIL10n15.data) === null || _wteAIL10n15$data === void 0 ? void 0 : _wteAIL10n15$data.color) !== null && _wteAIL10n$data$color5 !== void 0 ? _wteAIL10n$data$color5 : "#147dfe",
      display: 0,
      position: "top",
      align: "end"
    }
  },
  plugins: {
    datalabels: {
      color: "#36A2EB",
      labels: {
        title: {
          font: {
            weight: "bold",
          }
        },
        value: {
          color: "green"
        }
      }
    }
  }
}, wteAIChart.update = function (_ref3) {
  var _ref4 = _slicedToArray(_ref3, 2),
    key = _ref4[0],
    value = _ref4[1];

  wteAIChart.settings[key] = value;
  wteAIChart.chart.update();
}, wteAIChart.init = function (canvas) {
  var chartData = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var args = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  wteAIChart.chartData = chartData !== null && chartData !== void 0 ? chartData : wteAIChart.randomChartData();
  wteAIChart.settings.data.datasets[0].data = Object.values(wteAIChart.chartData).map(function (v) {
    return v.altitude;
  });
  wteAIChart.settings.data.labels = Object.entries(wteAIChart.chartData).map(function (_ref5) {
    var _ref6 = _slicedToArray(_ref5, 2),
      i = _ref6[0],
      v = _ref6[1];

    return v.label;
  });

  // Create gradient background
  var ctx = canvas.getContext('2d');
  var gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
  gradient.addColorStop(0, _wteAIL10n4$data.color + "3D");
  gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

  wteAIChart.settings.data.datasets[0].backgroundColor = gradient;

  const originalStroke = ctx.stroke;
  ctx.stroke = function () {
    ctx.save();
    ctx.shadowColor = 'rgba(0, 0, 0, 0.25)';
    ctx.shadowBlur = 10;
    ctx.shadowOffsetX = 0;
    ctx.shadowOffsetY = 16;
    originalStroke.apply(this, arguments);
    ctx.restore();
  };

  wteAIChart.chart = new Chart(canvas, _objectSpread(_objectSpread({}, wteAIChart.settings), args));

}, wteAIChart.randomize = function () {
  var unit = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "m";
  wteAIChart.chartData = wteAIChart.randomChartData(unit);
  wteAIChart.settings.data.datasets[0].data = Object.values(wteAIChart.chartData).map(function (v) {
    return v.altitude;
  });
  wteAIChart.chart.data.datasets[0].data = wteAIChart.settings.data.datasets[0].data;
  wteAIChart.chart.update();
}, wteAIChart.randomChartData = function () {
  var unit = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "m";
  var chartData = {},
    units = {
      m: 1,
      ft: 3.28
    };
  Object.keys(wteAIL10n.chartData).forEach(function (count) {
    chartData = _objectSpread(_objectSpread({}, chartData), {}, _defineProperty({}, count, {
      at: wteAIL10n.chartData[count].at,
      altitude: wteAIL10n.chartData[count].altitude,
      unit: unit,
      label: wteAIL10n.chartData[count].label
    }));
  });
  return chartData;
};

var setDefaultUnit = function setDefaultUnit() {
  var _wteAIL10n$alt_unit2, _wteAIL10n16;

  var defaultUnitRadio = document.querySelector("[value=".concat((_wteAIL10n$alt_unit2 = (_wteAIL10n16 = wteAIL10n) === null || _wteAIL10n16 === void 0 ? void 0 : _wteAIL10n16.alt_unit) !== null && _wteAIL10n$alt_unit2 !== void 0 ? _wteAIL10n$alt_unit2 : "m", "]"));
  if (defaultUnitRadio) defaultUnitRadio.checked = true;
};

var onTabLoad = function onTabLoad() {
  var _document$querySelect;

  var ctx = document.getElementById("wteAltChart").getContext("2d");
  ctx && wteAIChart.init(ctx, null), setDefaultUnit();
  (_document$querySelect = document.querySelectorAll(".wpte-color-picker")) === null || _document$querySelect === void 0 ? void 0 : _document$querySelect.forEach(function (el) {
    jQuery.fn.wpColorPicker && jQuery(el).wpColorPicker({
      change: function change(e, ui) {
        var _wteAIChart$chart, _wteAIChart$chart$dat;

        var color = ui.color.toString();
        wteAIChart === null || wteAIChart === void 0 ? void 0 : (_wteAIChart$chart = wteAIChart.chart) === null || _wteAIChart$chart === void 0 ? void 0 : (_wteAIChart$chart$dat = _wteAIChart$chart.data) === null || _wteAIChart$chart$dat === void 0 ? void 0 : _wteAIChart$chart$dat.datasets.forEach(function (ds) {
          ds.backgroundColor = color + "33";
          ds.borderColor = color;
          ds.datalabels.labels.name.color = color;
          ds.datalabels.labels.value.color = color;
        });

        if (!!wteAIChart.chart) {
          var _wteAIChart$settings, _wteAIChart$settings$, _wteAIChart$settings$2, _wteAIChart$settings2, _wteAIChart$settings3, _wteAIChart$settings4, _wteAIChart$settings5, _wteAIChart$settings6, _wteAIChart$settings7;

          wteAIChart.chart.options.elements.point.backgroundColor = color;
          wteAIChart.update("data", wteAIChart.chart.data);
          wteAIChart.update("options", wteAIChart.chart.options);
          var previewBtn = document.getElementById("wte-chart-preview-btn");
          previewBtn.style.background = (wteAIChart === null || wteAIChart === void 0 ? void 0 : (_wteAIChart$settings = wteAIChart.settings) === null || _wteAIChart$settings === void 0 ? void 0 : (_wteAIChart$settings$ = _wteAIChart$settings.data) === null || _wteAIChart$settings$ === void 0 ? void 0 : (_wteAIChart$settings$2 = _wteAIChart$settings$.datasets[0]) === null || _wteAIChart$settings$2 === void 0 ? void 0 : _wteAIChart$settings$2.borderColor[0]) + "33";
          previewBtn.style.borderColor = wteAIChart === null || wteAIChart === void 0 ? void 0 : (_wteAIChart$settings2 = wteAIChart.settings) === null || _wteAIChart$settings2 === void 0 ? void 0 : (_wteAIChart$settings3 = _wteAIChart$settings2.data) === null || _wteAIChart$settings3 === void 0 ? void 0 : (_wteAIChart$settings4 = _wteAIChart$settings3.datasets[0]) === null || _wteAIChart$settings4 === void 0 ? void 0 : _wteAIChart$settings4.borderColor[0];
          previewBtn.style.color = wteAIChart === null || wteAIChart === void 0 ? void 0 : (_wteAIChart$settings5 = wteAIChart.settings) === null || _wteAIChart$settings5 === void 0 ? void 0 : (_wteAIChart$settings6 = _wteAIChart$settings5.data) === null || _wteAIChart$settings6 === void 0 ? void 0 : (_wteAIChart$settings7 = _wteAIChart$settings6.datasets[0]) === null || _wteAIChart$settings7 === void 0 ? void 0 : _wteAIChart$settings7.borderColor[0];
        }
      }
    });
  });
  var uploaderBtn = document.getElementById("wte-btn-chart-bg-uploader"),
    deleteBtn = document.getElementById("wte-btn-chart-bg-delete");

  var chartBgHandler = function chartBgHandler() {
    return {
      frame: null,
      chartContainer: document.getElementById("altitiude-chart-screen-wrapper"),
      input: document.getElementById("wp_travel_engine_settings[wte_advance_itinerary][chart][bg]")
    };
  };

  var chartBg = chartBgHandler();

  var uploadHandler = function uploadHandler(e) {
    e.preventDefault();

    if (chartBg.frame) {
      chartBg.frame.open();
      return;
    }

    chartBg.frame = wp.media({
      multiple: false
    });
    chartBg.frame.open().on("select", function (e) {
      var _image$attributes;

      var image = chartBg.frame.state().get("selection").first();

      if (image === null || image === void 0 ? void 0 : (_image$attributes = image.attributes) === null || _image$attributes === void 0 ? void 0 : _image$attributes.id) {
        chartBg.chartContainer && (chartBg.chartContainer.style.backgroundImage = image && "url(".concat(image.attributes.url, ")"));
        chartBg.input && (chartBg.input.value = image.attributes.id);
      }

      deleteBtn && deleteBtn.style.removeProperty("display");
    });
  };

  var removehandler = function removehandler(e) {
    e.preventDefault();
    var input = chartBg.input,
      frame = chartBg.frame,
      chartContainer = chartBg.chartContainer;
    input && (input.value = "");
    chartContainer && chartContainer.style.removeProperty("background-image");
    deleteBtn.style.display = "none";
  };

  uploaderBtn === null || uploaderBtn === void 0 ? void 0 : uploaderBtn.addEventListener("click", uploadHandler);
  deleteBtn && (chartBg === null || chartBg === void 0 ? void 0 : chartBg.input.value.length) < 1 && (deleteBtn.style.display = "none");
  deleteBtn === null || deleteBtn === void 0 ? void 0 : deleteBtn.addEventListener("click", removehandler);
};

window.addEventListener("load", function () {
  var _wteAIL10n17;

  var ctx = document.getElementById("wteAltChart");
  var screen = document.getElementById("altitude-chart-screen");
  var chartData = null;

  try {
    if (wteAIL10n && wteAIL10n.chartData) {
      if (wteAIL10n.chartData[0]) {
        chartData = JSON.parse(wteAIL10n.chartData[0]);
        var dataLength = Object.keys(chartData).length;

        if (!!screen) {
          screen.style.width = "".concat(dataLength * 100, "px");
        }
      }
    }
  } catch (error) {
    console.log("Its okay if is admin settings.", error);
  }

  ctx && wteAIChart.init(ctx, chartData), setDefaultUnit();
  var allUnitSwitches = null;
  var selected_alt_unit = ((_wteAIL10n17 = wteAIL10n) === null || _wteAIL10n17 === void 0 ? void 0 : _wteAIL10n17.alt_unit) || 'm';
  var units = {
    m: selected_alt_unit == "m" ? 1 : 0.3048,
    ft: selected_alt_unit == "ft" ? 1 : 3.28084
  };
  var updateChartSettingOptions = {
    "scales.xAxes.display": function scalesXAxesDisplay(el) {
      wteAIChart.chart.options.scales.xAxes.forEach(function (xa) {
        return xa.display = el.checked;
      });
      wteAIChart.update("options", wteAIChart.chart.options);
    },
    "scales.yAxes.display": function scalesYAxesDisplay(el) {
      wteAIChart.chart.options.scales.yAxes.forEach(function (xa) {
        return xa.display = el.checked;
      });
      wteAIChart.update("options", wteAIChart.chart.options);
    },
    "datasets.data.fill": function datasetsDataFill(el) {
      wteAIChart.chart.data.datasets.forEach(function (ds) {
        return ds.fill = !el.checked;
      });
      wteAIChart.update("data", wteAIChart.chart.data);
    }
  };
  document.addEventListener("change", function (e) {
    if (e.target.type === "radio" && e.target.name === "elevation-unit") {
      var _wteAIChart$chart2, _wteAIChart$chart2$da;

      wteAIChart === null || wteAIChart === void 0 ? void 0 : (_wteAIChart$chart2 = wteAIChart.chart) === null || _wteAIChart$chart2 === void 0 ? void 0 : (_wteAIChart$chart2$da = _wteAIChart$chart2.data) === null || _wteAIChart$chart2$da === void 0 ? void 0 : _wteAIChart$chart2$da.datasets.map(function (ds) {
        var _allUnitSwitches, _allUnitSwitches2;

        allUnitSwitches = (_allUnitSwitches = allUnitSwitches) !== null && _allUnitSwitches !== void 0 ? _allUnitSwitches : Array.from(document.querySelectorAll("[name=elevation-unit]"));
        var checkedUnit = (_allUnitSwitches2 = allUnitSwitches) === null || _allUnitSwitches2 === void 0 ? void 0 : _allUnitSwitches2.find(function (el) {
          return el.checked;
        }).value;
        ds.data = Object.values(wteAIChart.chartData).map(function (v) {
          return Math.floor(+v.altitude * units[checkedUnit]);
        }), ds.datalabels = wteAIChart.datalabels(checkedUnit);
        return ds;
      });
      wteAIChart.update(["data", wteAIChart.chart.data]);
    }

    if (Array.from(e.target.classList).includes("wte-chart-config-input")) {
      e.target.dataset.settings && updateChartSettingOptions[e.target.dataset.settings](e.target);
    }
  });
  document.addEventListener("click", function (e) {
    if (e.target.id === "wte-chart-preview-btn") {
      e.preventDefault();

      if (Array.from(e.target.classList).includes("chart-is-active")) {
        wteAIChart.randomize(document.querySelector("[name=elevation-unit]:checked").value);
        return;
      }
    }
  });
  window.wpte_add_action && wpte_add_action("wpte_after_global_settings_tab_shown", "onTabLoad");
});
