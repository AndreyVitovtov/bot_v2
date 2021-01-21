"use strict";

$(document).ready(function() {
    let str = $('.str').length;

    $('body').on('click', '.add-right', function() {
        let b = $('.button-bot').length;
        let className = "."+$(this).data('class');
        $(className).append("<div class='button-bot button"+b+"'>" +
            "<div class='del-button' data-class='button"+b+"'>" +
            "<i class='icon-cancel-outline'></i>" +
            "</div>" +
            "<input type='text' " +
            "value='text button' " +
            "class='text-button' " +
            "data-button='button"+b+"'" +
            "placeholder='Text button'>" +
        "</div>");

        $('.block-buttons-bot').append("<div class='block-name-button block-button"+b+"'>"+
            "<label class='label-button"+b+"'>text button</label>"+
            "<input type='text' " +
            "class='input-button"+b+"'" +
            "placeholder='Name button'>" +
            "</div>");

        b += 1;
    });

    $('body').on('keyup', '.text-button', function() {
       let className = $(this).data('button');
       let text = $(this).val();
       if(text.length === 0) {
           text = "no text";
       }
       $('.label-'+className).html(text);
    });

    $('body').on('click', '#add-bottom', function() {
        $('.str0').append('<div class="str str'+str+'">\n' +
            '            <div class="buttons buttons'+str+'"></div>\n' +
            '        </div>\n' +
            '        <div class="add add-right str'+str+'" data-class="buttons'+str+'">\n' +
            '            <i class="icon-right-outline"></i>\n' +
            '        </div>'+
            '<div class="add del-str str'+str+'" data-class="str'+str+'">\n' +
        '            <i class="icon-cancel-outline"></i>\n' +
        '        </div>');

        str += 1;
    });

    $('body').on('click', '.del-button', function() {
       let className = $(this).data('class');
       $('.'+className).remove();
       $('.block-'+className).remove();
    });

    $('body').on('click', '.del-str', function() {
       let className = $(this).data('class');
       let b;
       let inputs = Array.from($('.'+className).find('input'));
       inputs.forEach(function(i) {
          b = $(i).data('button');
          $('.block-'+b).remove();
       });

       $('.'+className).remove();
    });

    $('body').on('click', '.save-menu', function() {
        let menuName = $('#menu-name').val();
        if(menuName.length === 0) {
            popUpWindow("Заполните имя меню");
            return;
        }

        let obj = {};
        let strings = Array.from($(".str"));
        let inputs;
        let i = 0;
        let s;
        let name;
        let classInput;
        let error = false;
        strings.pop();
        strings.forEach(function(item) {
            inputs = Array.from($(item).find('input'));
            if(inputs.length > 0) {
                s = 0;
                obj[i] = {};
                inputs.forEach(function(input) {
                    if(input.value.length > 0) {
                        classInput = $(input).data('button');
                        name = $('.input-'+classInput).val();

                        if(name.length < 1) error = true;
                        if(input.value.length < 1) error = true;

                        obj[i][s] = {};
                        obj[i][s]['text'] = input.value;
                        obj[i][s]['name'] = name;
                        s++;
                    }
                });
                i++;
            }
        });

        if(error) {
            popUpWindow("Заполните все тексты и имена кнопок");
            return false;
        }

        $.ajax({
            type: "GET",
            url: "/developer/menu/save",
            data: {
                'name' : menuName,
                'menu' : obj
            },
            success: function(data) {
                location.reload();
            }
        });
    });
});
