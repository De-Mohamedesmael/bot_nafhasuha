$(document).on("click", ".accordion-toggle", function () {
    let id = $(this).data("id");
    if ($(".angle-class-" + id).hasClass("fa-angle-right")) {
        $(".angle-class-" + id).removeClass("fa-angle-right");
        $(".angle-class-" + id).addClass("fa-angle-down");
    } else if ($(".angle-class-" + id).hasClass("fa-angle-down")) {
        $(".angle-class-" + id).removeClass("fa-angle-down");
        $(".angle-class-" + id).addClass("fa-angle-right");
    }
});

$(document).on("change", ".my-new-checkbox", function () {
    let parent_accordion = $(this).parent().parent();
    if ($(this).prop("checked") === true) {
        $(parent_accordion).find(".my-new-checkbox").prop("checked", true);
    } else {
        $(parent_accordion).find(".my-new-checkbox").prop("checked", false);
    }
});

$(document).ready(function () {
    $("#pct_modal_body .product_checkbox").each(function () {
        if ($(this).prop("checked") === true) {
            toggleAccordianTillItem($(this));
        }
    });
});
var doc_ready = 0;
var is_edit_page = $("#is_edit_page").val();
$(document).ready(function () {
    if (is_edit_page != "1") {
        $("#search_pct").val("");
        $("#search_pct").selectpicker("refresh");
        $(".product_checkbox").prop("checked", false);
    }
    doc_ready = 1;
});
$(document).on(
    "changed.bs.select",
    "select#search_pct",
    function (e, clickedIndex, isSelected, oldValue) {
        if (doc_ready === 1 && is_edit_page != "1") {
            let selectedOptionValue = $(this).val();
            $(".product_checkbox").prop("checked", false);

            for (var i = 0; i < selectedOptionValue.length; i++) {
                var val = selectedOptionValue[i];

                let product_name = $(
                    "#search_pct option[value='" + val + "']"
                ).text();

                let product_element = $(
                    '#accordian_div a:contains("' + product_name + '")'
                );
                let related_checkbox = $(product_element)
                    .parent()
                    .parent()
                    .find(".product_checkbox");
                $(related_checkbox).prop("checked", true);

                toggleAccordianTillItem($(related_checkbox));
            }
        }
    }
);

function toggleAccordianTillItem(product) {
    let class_level = $(product)
        .closest(".class_level")
        .find(".accordion-toggle")
        .data("id");
    let category_level = $(product)
        .closest(".category_level")
        .find(".accordion-toggle")
        .data("id");
    let sub_category_level = $(product)
        .closest(".sub_category_level")
        .find(".accordion-toggle")
        .data("id");
    let brand_level = $(product)
        .closest(".brand_level")
        .find(".accordion-toggle")
        .data("id");
    let top_accordion = $(product)
        .closest(".top_accordion")
        .find(".accordion-toggle")
        .data("id");
    $("#collapse" + class_level).collapse("show");
    $("#collapse" + category_level).collapse("show");
    $("#collapse" + sub_category_level).collapse("show");
    $("#collapse" + brand_level).collapse("show");
    $("#collapse" + top_accordion).collapse("show");
}
$("#submit-btn").on("click", function (e) {
    e.preventDefault();
        submitForm();
});

function submitForm() {
    if ($("#product-form").valid()) {
        tinyMCE.triggerSave();
        document.getElementById("loader").style.display = "block";
        document.getElementById("content").style.display = "none";
        $.ajax({
            type: "POST",
            url: $("form#product-form").attr("action"),
            data: $("#product-form").serialize(),
            success: function (response) {
                myFunction();
                if (response.success) {
                    swal("Success", response.msg, "success");
                    $("#sku").val("").change();
                    $("#name").val("").change();
                    $(".translations").val("").change();

                    if (!$('#clear_all_input_form').is(':checked')) {
                        $('.clear_input_form').val('');
                        $('.clear_input_form').selectpicker('refresh');
                    }
                    const previewContainer = document.querySelector('.preview-container');
                    previewContainer.innerHTML = '';
                } else {
                    swal("Error", response.msg, "error");
                }
            },
            error: function (response) {
                myFunction();
                if (!response.success) {
                    swal("Error", response.msg, "error");
                }
            },
        });
    }
}
