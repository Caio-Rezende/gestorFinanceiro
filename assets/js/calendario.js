$(function() {
    $('#calendario').fullCalendar({
        month: month,
        year: year,
        header: false,
        eventSources: [$('#calendario').attr('data-src')],
        editable: false,
        monthNames: monthNames,
        monthNamesShort: monthNamesShort,
        dayNames: dayNames,
        dayNamesShort: dayNamesShort,
        dayClick: function(date, allDay, jsEvent, view) {
            var tipoDte = $('[name="tipoDte"]:checked').val();
            document.location.href = 'index.php?control=ctConta&date=' + date.getTime()
                    + "&tipoDte=" + tipoDte;
        },
        eventRender: function (event, element) {
            element.find('.fc-event-title').html(event.title);
        }
    });
});