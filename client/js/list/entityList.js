(function (travi, framework) {
    "use strict";

    var constants = travi.constants,
        templates = travi.templates,
        pagination = travi.pagination,

        TEMPLATE_NAME = 'update-item',
        buttonText,

        $updateList;

    constants.set('PAGE_EVENT', 'page-loaded');

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


        $('ul.pagination a.more, ul.pagination a.prev').click(function () {
            var $this = $(this);

            $.getJSON($this.attr('href'), function (data) {
                var i,
                    updateContainer = data.updates.updateList,
                    updates = updateContainer.entities,
                    updateCount = updates.length;

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
            });

            return false;
        });
    }

    function requestAnnouncements(eventData) {
        $.getJSON(eventData.url, function (data) {
            var i,
                announcementsContainer = data.updates.updateList,
                announcements = announcementsContainer.entities,
                l = announcements.length;

            $updateList.hide('blind', function () {
                $updateList.empty();

                templates.get(TEMPLATE_NAME).then(function () {
                    for (i = 0; i < l; i += 1) {
                        $updateList.append(templates.render(TEMPLATE_NAME, {
                            list: announcementsContainer,
                            update:announcements[i]
                        }));
                    }
                });

                $updateList.show('blind', function () {
                    var list = data.updates.updateList,
                        offset = parseInt(list.offset, 10) || 0,
                        limit = parseInt(list.limit, 10),
                        total = parseInt(list.totalEntities, 10);

                    travi.publish(constants.get('PAGE_EVENT'), {
                        offset: offset,
                        nextOffset: offset + limit,
                        prevOffset: offset - limit,
                        total: total
                    });
                });
            });
        });
    }

    function init() {
        $updateList = $('ol.entityList');

        restyleRemove();
        $updateList.delegate('li.remove-item a.item-action', 'click', function () {
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
        travi.subscribe(pagination.events.NEXT_PAGE_REQUESTED, requestAnnouncements);
        travi.subscribe(pagination.events.PREV_PAGE_REQUESTED, requestAnnouncements);
    }

    $(init);

    framework.namespace('entityList', {
        init                    : init,
        setConfirmationMessage  : setMessage,
        setButtonText           : setText,
        getButtonText           : getText,
        constants               : constants,
        requestMoreAnnouncements: requestAnnouncements
    });
}(travi, travi.framework));