if (typeof travi !== 'object') {
	var travi = {};
}
if (typeof travi.framework !== 'object') {
    travi.framework = {};
}

travi.framework.entityList = (function () {
    "use strict";
    
    var  buttonText,

    setMessage = function (confirmation) {
        $("#confirmation").text(confirmation);
    },

    setText = function (text) {
        this.buttonText = text;
    },

    getText = function () {
        return this.buttonText;
    },

    confirm = function () {
        var $form = $(this);

        $("#confirmation").dialog("option", "buttons", [
            {
                text:   travi.framework.entityList.getButtonText(),
                click:  function () {
                    $(this).dialog("close");
                    $form.ajaxSubmit({
                        success: function (data, testStatus, xhr, $form) {
                            var $containingList = $form.closest('ol');
                            $form
                                .closest('li')
                                .parent()
                                .closest('li')
                                .remove();
                            $containingList.trigger('entityRemoved');
                        }
                    });
                }
            },
            {
                text:   "Cancel",
                click:  function () {
                    $(this).dialog("close");
                }
            }
        ]);
        $("#confirmation").dialog("open");

        return false;
    },

    init = function () {
        $("li.remove-item form")
            .hide()
            .after("<a class='item-action' href='#'>Remove</a>");
        $('ol.entityList').delegate('li.remove-item a.item-action', 'click', function () {
            $(this).prev("form").submit();
            return false;
        });
        $("body").append("<div id='confirmation' title='Are you sure?'></div>");
        $("#confirmation").dialog({
            autoOpen:   false,
            modal:      true,
            resizable:  false
        });
        $("form.item-action").submit(confirm);
        $('a.add-item').button({icons: {primary: 'ui-action-circle-plus'}});
    };

    $(document).ready(function () {
        init();
    });

    return {
        init                    : init,
        setConfirmationMessage  : setMessage,
        setButtonText           : setText,
        getButtonText           : getText
    };
}());