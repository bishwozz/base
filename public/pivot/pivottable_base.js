window.pivotTableHelper = (function() {
    var renderers = $.extend(
        $.pivotUtilities.renderers,
        $.pivotUtilities.c3_renderers,
        // $.pivotUtilities.d3_renderers,
        $.pivotUtilities.export_renderers
    );
    var aggregators = {
        "Count": $.pivotUtilities.aggregators.Count,
        "Sum": $.pivotUtilities.aggregators.Sum,
        "Maximum": $.pivotUtilities.aggregators.Maximum,
        "Average": $.pivotUtilities.aggregators.Average,
        "Minimum": $.pivotUtilities.aggregators.Minimum,
        "Count as Fraction of Columns": $.pivotUtilities.aggregators["Count as Fraction of Columns"],
        "Count as Fraction of Rows": $.pivotUtilities.aggregators["Count as Fraction of Rows"],
        "Count as Fraction of Total": $.pivotUtilities.aggregators["Count as Fraction of Total"],
        "Sum as Fraction of Columns": $.pivotUtilities.aggregators["Sum as Fraction of Columns"],
        "Sum as Fraction of Rows": $.pivotUtilities.aggregators["Sum as Fraction of Rows"],
        "Sum as Fraction of Total": $.pivotUtilities.aggregators["Sum as Fraction of Total"]
    };
    var derivers = $.pivotUtilities.derivers;

    var parseAndPivot = function(options, pivotOpt) {
        $('#output').html('<div class="text-center"><img src="/css/images/loading.gif"/></div>');
        // $("#output").html("<p align='center' style='color:grey;'>(processing...)</p>")
        Papa.parse(options.pivotDataCSV, {
            skipEmptyLines: true,
            download: false,
            error: function(e) {
                alert(e)
            },
            complete: function(parsed) {
                // console.log(parsed.data);
                $("#output").pivotUI(
                    parsed.data,
                    pivotOpt,
                    true
                );
            }

        });
    };
    var preparePivotOptions = function(opts) {
        var pivotOpt = {
            rows: opts["rows"] ? opts["rows"] : [],
            cols: opts["cols"] ? opts["cols"] : [],
            renderers: opts["renderers"] ? opts["renderers"] : [],
            aggregators: opts["aggregators"] ? opts["aggregators"] : [],
            hiddenAttributes: opts["hiddenAttributes"] ? opts["hiddenAttributes"] : [],
            inclusions: opts["inclusions"] ? opts["inclusions"] : {},
            sorters: opts["sorters"] ? opts["sorters"] : {},
            derivedAttributes: opts["derivedAttributes"] ? opts["derivedAttributes"] : {},
        };
        //prepare the additional attributes
        //prepare [derivedAttributes] from options.derievedMapping
        if (opts.derivedMapping !== undefined) {
            $.each(opts.derivedMapping, function(key, item) {
                pivotOpt.derivedAttributes[key] = function(record) {
                    if (opts.masterData[item.master][record[item.data_field]] !== undefined)
                        return opts.masterData[item.master][record[item.data_field]];
                    else
                        return "N/A";
                };
                //add in hiddenAttributes
                pivotOpt.hiddenAttributes.push(item.data_field);
            });
        }
        //preparing [derivedAttributes] from [opts.labels]
        if (opts.labels !== undefined) {
            $.each(opts.labels, function(key, item) {
                pivotOpt.derivedAttributes[item] = function(record) {
                    return record[key] ? record[key] : "N/A";
                };
                pivotOpt.hiddenAttributes.push(key);
            });
        }

        //adding the default Count Aggregators.
        if (opts.addCountAggregators === undefined || opts.addCountAggregators) {
            pivotOpt = addCountAggregators(pivotOpt);
        }


        pivotOpt.renderers = renderers;
        pivotOpt.onRefresh = function(config) {
            var config_copy = JSON.parse(JSON.stringify(config));
            //delete some values which are functions
            delete config_copy["aggregators"];
            delete config_copy["renderers"];
            //delete some bulky default values
            delete config_copy["rendererOptions"];
            delete config_copy["localeStrings"];
            $("#config").text(JSON.stringify(config_copy, undefined, 2));
            if ($(".pvtHeader").length == 0) {
                //TABLE
                $("<div class='pvtHeader' style='text-align: center; font-weight: bold, font-size:18px;'></div>").insertBefore("table.pvtTable");
                $("<div class='pvtFilters' style='text-align: center; font-weight:bold'></div>").insertBefore("table.pvtTable");
                //CHART (C3)
                $(".pvtRendererArea div:first-child p:first-child").remove();
                $("<div class='pvtHeader' style='text - align: center; font-weight:bold, font-size:18px;'></div>").insertBefore("div.c3");
                $("<div class='pvtFilters' style='text-align: center; font-weight:bold'></div>").insertBefore("div.c3");
            }
            var cols = config.cols.join("/"),
                rows = config.rows.join("/");
            cols = cols.length > 0 ? "[" + cols + "]" : "";
            rows = rows.length > 0 ? "[" + rows + "]" : "";
            if (config.vals.length > 0) {
                $(".pvtHeader").html("<b>Report Name :</b>" + config.aggregatorName + " of " + config.vals[0] + ' for ' + cols + " by " + rows);
            } else {
                $(".pvtHeader").html("<b>Report Name :</b>" + config.aggregatorName + " of " + cols + " by " + rows);
            }
            // for(var i=0, len = config.in)
            var incTxt = "",
                incArr = [];
            $.each(config.inclusions, function(key, item) {
                incArr.push("<b style='margin-left:30px;'>" + key + ":</b>" + item.join(", "));
            });
            if (incArr.length > 0)
                incArr.splice(0, 0, "<b style='font-size:20px;'> Criteria(s)</b>");
            incTxt = incArr.join("<BR/>");
            $(".pvtFilters").html(incTxt);
        }
        return pivotOpt;
    };

    var addCountAggregators = function(pivotOpt) {
        $.each(aggregators, function(key, item) {
            pivotOpt.aggregators[key] = item;
        });
        return pivotOpt;
    };

    var loadAndRenderMasterAndData = function(options) {
        var ajxData = options.pivotDataUrl,
        ajxMaster = options.masterDataUrl;
        $.when(ajxData, ajxMaster).done(function(ajxData, ajxMaster) {
            options.pivotDataCSV = ajxData;
            options.masterData = ajxMaster;
            parseAndPivot(options, preparePivotOptions(options));
        });
    };
    var loadAndRenderData = function(pivotDataUrl) {

    };
    return {
        loadAndRender: function(options) {
            loadAndRenderMasterAndData(options);
        }
    };

})();