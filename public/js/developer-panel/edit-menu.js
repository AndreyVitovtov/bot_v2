"use strict"
let str;
let b;

$(document).ready(function() {

    $('body').on('change', '#select-menu', function () {
        getMenu();
    });

    $('body').on('click', '.add-right', function () {
        b++;
        let className = "." + $(this).data('class');
        $(className).append("<div class='button-bot button" + b + "'>" +
            "<div class='del-button' data-class='button" + b + "'>" +
            "<i class='icon-cancel-outline'></i>" +
            "</div>" +
            "<input type='text' " +
            "value='text button' " +
            "class='text-button' " +
            "data-button='button" + b + "'" +
            "placeholder='Text button'>" +
            "</div>"
        );

        $('.block-buttons-bot').append("<div class='block-name-button block-button" + b + "'>" +
            "<label class='label-button" + b + "'>text button</label>" +
            "<input type='text' " +
            "class='input-button" + b + "'" +
            "placeholder='Name button'>" +
            "</div>"
        );
    });

    $('body').on('keyup', '.text-button', function () {
        let className = $(this).data('button');
        let text = $(this).val();
        if (text.length === 0) {
            text = "no text";
        }
        $('.label-' + className).html(text);
    });

    $('body').on('click', '#add-bottom', function () {
        $('.str0').append('<div class="str str' + str + '">\n' +
            '            <div class="buttons buttons' + str + '"></div>\n' +
            '        </div>\n' +
            '        <div class="add add-right str' + str + '" data-class="buttons' + str + '">\n' +
            '            <i class="icon-right-outline"></i>\n' +
            '        </div>' +
            '<div class="add del-str str' + str + '" data-class="str' + str + '">\n' +
            '            <i class="icon-cancel-outline"></i>\n' +
            '        </div>');

        str += 1;
    });

    $('body').on('click', '.del-button', function () {
        let className = $(this).data('class');
        $('.' + className).remove();
        $('.block-' + className).remove();
    });

    $('body').on('click', '.del-str', function () {
        let className = $(this).data('class');
        let d;
        let inputs = Array.from($('.' + className).find('input'));
        inputs.forEach(function (i) {
            d = $(i).data('button');
            $('.block-' + d).remove();
        });

        $('.' + className).remove();
    });

    $('body').on('click', '#reset-menu', function() {
        getMenu();
    });

    $('body').on('click', '#save-edit-menu', function() {
        let menuName = $('#select-menu').val();
        if(menuName.length === 0) {
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
                    else {
                        error = true;
                    }
                });
                i++;
            }
        });
        // console.log(JSON.stringify(obj));

        if(error) {
            popUpWindow("Заполните все тексты и имена кнопок");
            return false;
        }
        $.ajax({
            type: "GET",
            url: "/developer/menu/edit/save",
            data: {
                'name' : menuName,
                'menu' : obj
            },
            success: function(data) {
                // console.log(data);
                popUpWindow("Сохранено");
            }
        });
    });

    $('body').on('click', '#delete-menu', function() {
        let menuName = $('#select-menu').val();

        $.ajax({
            'type': 'GET',
            'url': '/developer/menu/delete',
            'data': {
                'menuName' : menuName
            },
            success: function(data) {
                location.reload();
            }
        });
    });
});

function getMenu() {
    let menuName = $('#select-menu').val();

    $('.example-menu').html('<div class="flex">\n' +
        '            <div class="menu-bot">\n' +
        '                <div>\n' +
        '                    <div class="str">\n' +
        '                        <div class="buttons buttons1"></div>\n' +
        '                    </div>\n' +
        '                    <div class="add add-right" data-class="buttons1">\n' +
        '                        <i class="icon-right-outline"></i>\n' +
        '                    </div>\n' +
        '                </div>\n' +
        '                <div class="str0">\n' +
        '                </div>\n' +
        '                <div>\n' +
        '                    <div class="str" id="str">\n' +
        '                    </div>\n' +
        '                    <div class="add add_bottom" id="add-bottom">\n' +
        '                        <i class="icon-down-outline"></i>\n' +
        '                    </div>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '            <div class="block-buttons-bot">\n' +
        '            </div>\n' +
        '        </div>\n' +
        '        <br>\n' +
        '        <br>\n' +
        '        <button class="button" id="save-edit-menu">Сохранить</button>\n' +
        '        <button class="button" id="reset-menu">Сброс</button>\n' +
        '        <button class="button button-red" id="delete-menu">Удалить</button>');

    $.ajax({
        'type': 'GET',
        'url': '/developer/menu/get',
        'data': {
            'menu': menuName
        },
        success: function (data) {
            $('.buttons1').html("");
            $('.str0').html("");
            $('.block-buttons-bot').html("");

            let menu = JSON.parse(data);
            let s = 1;
            let i = 1;
            menu.forEach(function (str) {
                if (s !== 1) {
                    $('.str0').append('<div class="str str' + s + '">' +
                        '<div class="buttons buttons' + s + '"></div>' +
                        '        </div>\n' +
                        '        <div class="add add-right str' + s + '" data-class="buttons' + s + '">\n' +
                        '            <i class="icon-right-outline"></i>\n' +
                        '        </div><div class="add del-str str' + s + '" data-class="str' + s + '">\n' +
                        '            <i class="icon-cancel-outline"></i>\n' +
                        '        </div>');
                }
                str.forEach(function (button) {
                    $('.buttons' + s).append('<div class="button-bot button' + i + '">' +
                        '<div class="del-button" data-class="button' + i + '">' +
                        '<i class="icon-cancel-outline"></i>' +
                        '</div>' +
                        '<input type="text" readonly value="' + button.text + '" class="text-button" data-button="button' + i + '" placeholder="Text button"></div>');

                    $('.block-buttons-bot').append("<div class='block-name-button block-button" + i + "'>" +
                        "<label class='label-button" + i + "'>" + button.text + "</label>" +
                        "<input type='text' readonly " +
                        "value='" + button.name + "'" +
                        "class='input-button" + i + "'" +
                        "placeholder='Name button'>" +
                        "</div>"
                    );

                    i++;
                });
                s++;
            });

            str = $('.str').length;
            b = $('.button-bot').length;
        }
    });
}
