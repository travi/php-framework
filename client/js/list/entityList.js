travi.framework.entityList = (function () {
    "use strict";
    
    var buttonText,

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
                        beforeSubmit: function (data, $form) {
                            $form
                                .closest('li')
//                                    .find('a').hide().end()
                                    .append('<img src="/resources/shared/img/progress/ajax-spinner.gif" class="loading-indicator"/>');
                        },
                        success: function (data, testStatus, xhr, $form) {
                            var $containingList = $form.closest('ol');

                            $form
                                .closest('li')
                                .parent()
                                .closest('li')
                                .slideUp('slow', function () {
                                    $(this).remove();
                                    $containingList.trigger('entityRemoved');
                                });
                        },
                        dataType: 'json'
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

    initPagination = function () {
        $('#moreUpdates').click(function () {
            var $this = $(this);

            $.getJSON($this.attr('href'), function (data) {
                var i,
                    updates = data.updates.updateList.entities,
                    updateCount = updates.length,
                    $updateList = $('ol.entityList');

                for (i = 0; i < updateCount; i = i + 1) {
                    $updateList.append('<li>&nbsp;</li>');
                }

                $this.trigger('updates-loaded');
            });

            return false;
        });
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
        initPagination();
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