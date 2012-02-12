(function (framework) {
    "use strict";

    var TEMPLATE_NAME = 'update-item',
        buttonText,
        constants = travi.constants,
        templates = travi.templates;

    constants.set('PAGE_EVENT', 'updates-loaded');
    constants.set('HIDDEN_CLASS', 'outOfRange');

    function setMessage(confirmation) {
        $("#confirmation").text(confirmation);
    }

    function setText(text) {
        buttonText = text;
    }

    function getText() {
        return buttonText;
    }

    function confirm(event) {
        var $form = $(event.target);

        $("#confirmation").dialog("option", "buttons", [
            {
                text:   getText(),
                click:  function () {
                    $(this).dialog("close");
                    $form.ajaxSubmit({
                        beforeSubmit: function (data, $form) {
                            $form
                                .closest('li')
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
    }

    function restyleRemove() {
        $("li.remove-item form:visible")
            .hide()
            .after("<a class='item-action' href='#'>Remove</a>");
    }

    function initPagination() {
        $.views.registerHelpers({
            toLower: function (string) {
                return string.toLowerCase();
            }
        });


        $('ul.pagination a.more, ul.pagination a.prev').click(function () {
            var $this = $(this);

            $.getJSON($this.attr('href'), function (data) {
                var i,
                    updateContainer = data.updates.updateList,
                    updates = updateContainer.entities,
                    updateCount = updates.length,
                    $updateList = $('ol.entityList'),
                    $prevUpdates = $('#previousUpdates'),
                    $divider = $('.pipeDivider');

                templates.get(TEMPLATE_NAME).then(function () {
                    for (i = 0; i < updateCount; i = i + 1) {
                        $updateList.append(
                            templates.render(
                                TEMPLATE_NAME,
                                {
                                    list: updateContainer,
                                    update: updates[i]
                                }
                            )
                        );
                    }
                    restyleRemove();
                });

                if (updateContainer.offset <= 0 || !updateContainer.offset) {
                    $prevUpdates.parent().addClass(constants.get('HIDDEN_CLASS'));
                    $divider.addClass(constants.get('HIDDEN_CLASS'));
                } else {
                    $prevUpdates.parent().removeClass(constants.get('HIDDEN_CLASS'));
                    $divider.removeClass(constants.get('HIDDEN_CLASS'));
                }

                $this.trigger(constants.get('PAGE_EVENT'));
            });

            return false;
        });
    }

    function init() {
        templates.preLoad(TEMPLATE_NAME, '/resources/templates/admin/update-item.tmpl');
        restyleRemove();
        $('ol.entityList').delegate('li.remove-item a.item-action', 'click', function () {
            $(this).prev("form").submit();
            return false;
        }).delegate("form.item-action", 'submit', confirm);
        $("body").append("<div id='confirmation' title='Are you sure?'></div>");
        $("#confirmation").dialog({
            autoOpen:   false,
            modal:      true,
            resizable:  false
        });
        $('a.add-item').button({icons: {primary: 'ui-action-circle-plus'}});
//        initPagination();
    }

    $(init);

    framework.namespace('entityList', {
        init                    : init,
        setConfirmationMessage  : setMessage,
        setButtonText           : setText,
        getButtonText           : getText,
        constants               : constants
    });
}(travi.framework));