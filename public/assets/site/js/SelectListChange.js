//by reagz: automatic parsing of dropdownlist or select list option 

function selectListChange(element, url, param, defaultOptionName) 
{


    var elementName = "#" + element;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                // $(elementName).append('<option value=""></option>');
                $.map(data, function (item) 
                {      
                        $(elementName).append($("<option/>").val(item.value).text(item.text));
                });
        }
       
    }); 



    $(elementName).trigger("change");
}
function selectListClassChange(element, url, param, defaultOptionName) 
{


    var elementName = "." + element;

    if ($("." + element).length == 0) 
    {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                // $(elementName).append('<option value=""></option>');
                $.map(data, function (item) 
                {      
                        $(elementName).append($("<option/>").val(item.value).text(item.text));
                });
        }
       
    }); 



    $(elementName).trigger("change");
}
function selectListChange2(element1, element2, url, param, defaultOptionName) 
{

    $(element1).find("select[name='"+element2+"']").append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(element1).find("select[name='"+element2+"']").empty();
                // $(elementName).append('<option value=""></option>');
                $.map(data, function (item) 
                {      
                    $(element1).find("select[name='"+element2+"']").append($("<option/>").val(item.value).text(item.text));
                });
        }
       
    }); 



    $(elementName).trigger("change");
}

function selectListChangeSelected(element,element1, url, param, defaultOptionName) 
{


    var elementName = "#" + element;
    var elementName1 = "#" + element1;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }
    if ($("#" + element1).length == 0) 
    {
        elementName1 = "." + element1;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));

    $(elementName1 + "> option").remove();
    $(elementName1).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                $(elementName1).empty();
                // $(elementName).append('<option value=""></option>');
                $.map(data[0], function (item) 
                {      
                        $(elementName).append($("<option/>").val(item.value).text(item.text));
                });

                $.map(data[5], function (item) 
                {      
                        $(elementName1).append($("<option/>").val(item.value).text(item.text));
                });

                $(elementName+" [value='"+data[1]+"']").attr("selected","selected");
                $(elementName1+" [value='"+data[2]+"']").attr("selected","selected");
                $("#classification_id").val(data[3]);
                $("#registered_student_classification_name").val(data[4]);

                $("#registered_student_curriculum_name").empty();
                $.map(data[6], function (item) 
                {      
                        $("#registered_student_curriculum_name").append($("<option/>").val(item.value).text(item.text+' Effective Date '+item.text1));
                });


               $("#classification_level_id [value='"+data[1]+"']").attr("selected","selected");
               $("#is_new [value='"+data[20]+"']").attr("selected","selected");

                $("#registered_student_last_name").val(data[9].last_name);
                $("#registered_student_first_name").val(data[9].first_name);
                $("#registered_student_middle_name").val(data[9].middle_name);
                $("#registered_student_address").val(data[9].address);
                $("#registered_student_home_number").val(data[9].home_number);
                $("#registered_student_preferred_name").val(data[9].preferred_name);
                $("#registered_student_email_address").val(data[9].student_email_address);
                $("#registered_student_mobile_number").val(data[9].student_email_address);
                $("#registered_student_birthdate").val(data[9].birthdate);
                $("#registered_student_church_affiliation").val(data[9].church_affiliation);
                $("#registered_student_birth_place").val(data[9].birth_place)
                $("#registered_student_icard_number").val(data[9].icard_number);
                $("#registered_student_passport_number").val(data[9].passport_number);
                $("#registered_student_name_relation").val(data[9].name_relation);

                $("#gender_id [value='"+data[9].gender_id+"']").prop("selected",true);
                $("#religion_id [value='"+data[9].religion_id+"']").prop("selected",true);
                $("#citizenship_id [value='"+data[9].citizenship_id+"']").prop("selected",true);
                $("#living_with_id [value='"+data[9].living_with_id+"']").prop("selected",true);
                $("#suffix_id [value='"+data[9].suffix_id+"']").prop("selected",true);

                $("#registered_student_fathers_name").val(data[18].fathers_name);
                $("#registered_student_fathers_mobile_number").val(data[18].fathers_mobile_number);
                $("#registered_student_fathers_occupation").val(data[18].fathers_occupation);
                $("#registered_student_fathers_employer_name").val(data[18].fathers_employer_name);
                $("#registered_student_fathers_employer_no").val(data[18].fathers_employer_no);
                $("#registered_student_fathers_email_address").val(data[18].fathers_email_address);

                $("#registered_student_mothers_name").val(data[18].mothers_name);
                $("#registered_student_mothers_mobile_number").val(data[18].mothers_mobile_number);
                $("#registered_student_mothers_occupation").val(data[18].mothers_occupation);
                $("#registered_student_mothers_employer_name").val(data[18].mothers_employer_name);
                $("#registered_student_mothers_employer_no").val(data[18].mothers_employer_no);
                $("#registered_student_mothers_email_address").val(data[18].mothers_email_address);
        }
       
    }); 



    $(elementName).trigger("change");
}

function selectListChangeSelected1(element,element1, url, param, defaultOptionName) 
{


    var elementName = "#" + element;
    var elementName1 = "#" + element1;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }
    if ($("#" + element1).length == 0) 
    {
        elementName1 = "." + element1;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));

    $(elementName1 + "> option").remove();
    $(elementName1).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                $(elementName1).empty();
                // $(elementName).append('<option value=""></option>');
                $.map(data[0], function (item) 
                {      
                        $(elementName).append($("<option/>").val(item.value).text(item.text));
                });

                $.map(data[5], function (item) 
                {      
                        $(elementName1).append($("<option/>").val(item.value).text(item.text));
                });

                $(elementName+" [value='"+data[1]+"']").attr("selected","selected");
                $(elementName1+" [value='"+data[2]+"']").attr("selected","selected");
                $("#classification_id").val(data[3]);
                $("#registered_student_classification_name").val(data[4]);

                $("#registered_student_curriculum_name").empty();
                $.map(data[6], function (item) 
                {      
                        $("#registered_student_curriculum_name").append($("<option/>").val(item.value).text(item.text+' Effective Date '+item.text1));
                });


               $("#classification_level_id [value='"+data[1]+"']").attr("selected","selected");
               // $("#is_new [value='"+data[20]+"']").attr("selected","selected");

                $("#registered_student_last_name").val(data[9].last_name);
                $("#registered_student_first_name").val(data[9].first_name);
                $("#registered_student_middle_name").val(data[9].middle_name);
                $("#registered_student_address").val(data[9].address);
                $("#registered_student_home_number").val(data[9].home_number);
                $("#registered_student_preferred_name").val(data[9].preferred_name);
                $("#registered_student_email_address").val(data[9].student_email_address);
                $("#registered_student_mobile_number").val(data[9].student_email_address);
                $("#registered_student_birthdate").val(data[9].birthdate);
                $("#registered_student_church_affiliation").val(data[9].church_affiliation);
                $("#registered_student_birth_place").val(data[9].birth_place)
                $("#registered_student_icard_number").val(data[9].icard_number);
                $("#registered_student_passport_number").val(data[9].passport_number);
                $("#registered_student_name_relation").val(data[9].name_relation);

                $("#gender_id [value='"+data[9].gender_id+"']").prop("selected",true);
                $("#religion_id [value='"+data[9].religion_id+"']").prop("selected",true);
                $("#citizenship_id [value='"+data[9].citizenship_id+"']").prop("selected",true);
                $("#living_with_id [value='"+data[9].living_with_id+"']").prop("selected",true);
                $("#suffix_id [value='"+data[9].suffix_id+"']").prop("selected",true);

                $("#registered_student_fathers_name").val(data[18].fathers_name);
                $("#registered_student_fathers_mobile_number").val(data[18].fathers_mobile_number);
                $("#registered_student_fathers_occupation").val(data[18].fathers_occupation);
                $("#registered_student_fathers_employer_name").val(data[18].fathers_employer_name);
                $("#registered_student_fathers_employer_no").val(data[18].fathers_employer_no);
                $("#registered_student_fathers_email_address").val(data[18].fathers_email_address);

                $("#registered_student_mothers_name").val(data[18].mothers_name);
                $("#registered_student_mothers_mobile_number").val(data[18].mothers_mobile_number);
                $("#registered_student_mothers_occupation").val(data[18].mothers_occupation);
                $("#registered_student_mothers_employer_name").val(data[18].mothers_employer_name);
                $("#registered_student_mothers_employer_no").val(data[18].mothers_employer_no);
                $("#registered_student_mothers_email_address").val(data[18].mothers_email_address);
        }
       
    }); 



    $(elementName).trigger("change");
}

function selectListRoomChange(element, url, param, defaultOptionName) 
{


    var elementName = "#" + element;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                $(elementName).append('<option value="" selected style="display:none">Room</option>');
                $.map(data, function (item) 
                {      
                        $(elementName).append($("<option/>").val(item.value).text(item.text));
                });
        }
       
    }); 



    $(elementName).trigger("change");
}

function selectListSubjectChange(element, url, param, defaultOptionName) 
{


    var elementName = "#" + element;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                $(elementName).append('<option value="" selected style="display:none">Subject</option>');
                $.map(data, function (item) 
                {      
                        $(elementName).append($("<option/>").val(item.value).text(item.text));
                });
        }
       
    }); 



    $(elementName).trigger("change");
}


function selectDataChange(element, url, param, defaultOptionName) 
{


    
    var elementName = "#" + element;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                $.map(data, function (item) 
                {
                        $(elementName).append($("<option/>").val(item.value).text(item.text+" "+item.text1));
                        
                });
        }
       
    }); 



    $(elementName).trigger("change");
}


/*function selectListChange(element, url, defaultOptionName) {

   
    var elementName = "#" + element;

    if ($("#" + element).length == 0) {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));

    $.ajax({
        url: url, type: "POST", dataType: "json",async:false,
        success: function (response) {
            if (response.success == true)
            {
                $.map(response.data, function (item) {
                    if (item.value == response.selected) {
                        $(elementName).append($("<option selected />").val(item.value).text(item.text));
                    }
                    else {
                        $(elementName).append($("<option />").val(item.value).text(item.text));
                    }
                });
            }
        }
    });


    $(elementName).trigger("change");
}*/


    function selectListChangeMultiParam(element, url,param, defaultOptionName) {

        var elementName = "#" + element;

        if ($("#" + element).length == 0) {
            elementName = "." + element;
        }

        $(elementName + "> option").remove();
        $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));

        $.ajax({
            url: url, type: "POST", dataType: "json", async: false, data: JSON.stringify(param),
            contentType: "application/json; charset=utf-8",

            success: function (response) {
                if (response.success == true) {
                    $.map(response.data, function (item) {
                        if (item.value == response.selected) {
                            $(elementName).append($("<option selected />").val(item.value).text(item.text));
                        }
                        else {
                            $(elementName).append($("<option />").val(item.value).text(item.text));
                        }
                    });
                }
            }
        });

        $(elementName).trigger("change");
/*

        var elementName = "#" + element;
        if ($("#" + element) == null) {
            elementName = "." + element;
        }
        $(elementName + "> option").remove();
        $(elementName).append($("<option />").val("").text("--" + defaultOptionName + "--"));


        $.ajax({
            url: url,
            type: 'POST',
            data: JSON.stringify(param),
            contentType: "application/json; charset=utf-8"
        })
         .done(function (response) {
             var data = $.parseJSON(response);
             for (i = 0; i < data.length; i++) {
                 $(elementName).append($("<option />").val(data[i].Value).text(data[i].Text));
             }
         });

       /* $.get(url).done(function (response) {
            var data = $.parseJSON(response);
            for (i = 0; i < data.length; i++) {
                $(elementName).append($("<option />").val(data[i].Value).text(data[i].Text));
            }
        });
        */

    }

    function selectListChangeCurriculumSubject(element, url, param, defaultOptionName) 
    {


        var elementName = "#" + element;

        if ($("#" + element).length == 0) 
        {
            elementName = "." + element;
        }

        $(elementName + "> option").remove();
        $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));


         $.ajax({
            url: url,
            data: param,
            type: "GET", 
            dataType: "json",
            async:false,
            success: function (data) 
            {
                    $(elementName).empty();
                    // $(elementName).append('<option value=""></option>');
                    $.map(data, function (item) 
                    {      
                            $(elementName).append($("<option>").val(item.value).text(item.code+' - '+item.name));
                    });
            }
           
        }); 



        $(elementName).trigger("change");
    }
