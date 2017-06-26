YUI.add('moodle-qtype_ioshmultichoice-limitchoices', function(Y) {
    M.qtype_ioshmultichoice = M.qtype_ioshmultichoice || {};
    M.qtype_ioshmultichoice.limitchoices = M.qtype_ioshmultichoice.limitchoices || {};

    M.qtype_ioshmultichoice.limitchoices.init = function(args) {
        M.qtype_ioshmultichoice.limitchoices.limit = args.correctAnswers;

        var inputs = document.querySelectorAll('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type == 'checkbox') {
                inputs[i].addEventListener('click', M.qtype_ioshmultichoice.limitchoices.checkboxSelectedEventHandler);
            }
        }
    }

    M.qtype_ioshmultichoice.limitchoices.checkboxSelectedEventHandler = function(e) {
        var inputs = document.querySelectorAll('input');
        var limit = M.qtype_ioshmultichoice.limitchoices.limit;
        var checkboxes = [];
        var unselected = [];
        var selected = [];

        // Categorise inputs
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type == 'checkbox') {
                checkboxes.push(inputs[i]);

                if (inputs[i].checked) {
                    selected.push(inputs[i]);
                } else {
                    unselected.push(inputs[i]);
                }
            }
        }

        // Define behaviour
        if (selected.length == limit) {
            for (var i = 0; i < unselected.length; i++) {
                unselected[i].disabled = true;
            }
        } else if (selected.length < limit) {
            for (var i = 0; i < unselected.length; i++) {
                unselected[i].disabled = false;
            }
        }
    }
}, '@VERSION@', {
    "requires": []
});