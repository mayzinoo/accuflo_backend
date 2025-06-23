function createDateTimepicker(className){
    $(`.${className}`).flatpickr({
       enableTime: true,
       minuteIncrement: 1,
       minDate: "today"

    });

 }