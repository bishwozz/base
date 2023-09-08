let PROGRESSREPORT = {
    _formSaveSingleKey: 113, // F2
    _formSaveGroupKey: 83, // S

    validate: (wrapperElement) => {
        debugger;
        let valid = true;
        $(wrapperElement)
            .find("input, select, textarea,number,time")
            .each(function () {
                /**
                 * Validate if element has required attribute and no value/input given
                 */
                if (
                    $(this).attr("required") !== undefined &&
                    $(this).val() === ""
                ) {
                    valid = false;
                    $(this).addClass("is-invalid");
                    if ($(this).next().hasClass("select2")) {
                        $(this).next().addClass("is-invalid");
                    }
                } else {
                    $(this).removeClass("is-invalid");
                }
            });
        return valid;
    },
};

window.PROGRESSREPORT = PROGRESSREPORT;
