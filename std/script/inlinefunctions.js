function highlightEdit(editableObj) {
    // console.log(editableObj.innerHTML);
    // alert('hello');
    $(editableObj).attr("data-old_value");
    $(editableObj).css("background", "#FFF");
}


function saveInlineEdit(editableObj, column, id) {
    var newValue = $(editableObj).text(); // Get the new value

    if (newValue === $(editableObj).attr("data-old_value")) {
        return false;
    }

    $(editableObj).css("background", "#FFF url(images/loader.gif) no-repeat right");

    var dataToSend = {
        column: column,
        value: newValue,
        studentsubjectid: id,
    };

    $.ajax({
        url: "inlineEdit.php",
        method: "POST",
        data: dataToSend,
        success: function(response) {
            toastr.success("Data Update successfully!", "Success");
            refreshSubjectTable();
            $(editableObj).attr("data-old_value", newValue);
            $(editableObj).css("background", "#FDFDFD");
        },
    });
}