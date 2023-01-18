/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Version: 4.3.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v4.3/admin/
*/
Array.prototype.indexOf || (Array.prototype.indexOf = function(e) {
    var t = this.length >>> 0,
        a = Number(arguments[1]) || 0;
    for ((a = a < 0 ? Math.ceil(a) : Math.floor(a)) < 0 && (a += t); a < t; a++)
        if (a in this && this[a] === e) return a;
    return -1
}), "function" != typeof String.prototype.trim && (String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, "")
});
var handleBootstrapCombobox = function() {
        try {
            $(".combobox").combobox()
        } catch (e) {$.noop()}
    },
    handleSelect2 = function() {
        try {
            $(".default-select2").select2(), $(".multiple-select2").select2({
                placeholder: "Select a state"
            })
        } catch (e) {$.noop()}
    },
    handleFormPasswordIndicator = function() {
        "use strict";
        try {
            $("#password-indicator-default").passwordStrength(), $("#password-indicator-visible").passwordStrength({
                targetDiv: "#passwordStrengthDiv2"
            })
        } catch (e) {$.noop()}
        
    },
    handleDatepicker = function() {
        try {
            $.fn.datepicker.dates['fr'] = {
                days: ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"],
                daysShort: ["dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam."],
                daysMin: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
                months: ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"],
                monthsShort: ["janv.", "févr.", "mars", "avril", "mai", "juin", "juil.", "août", "sept.", "oct.", "nov.", "déc."],
                today: "Aujourd'hui",
                monthsTitle: "Mois",
                clear: "Effacer",
                weekStart: 1,
                format: "dd/mm/yyyy"
            };
            $("#datepicker-default").datepicker({
                format: "yyyy-mm-dd",
                orientation: "bottom",
                todayHighlight: !0,
                autoclose: !0
            }),
            $("#datepicker-birthdate").datepicker({
                format: "yyyy-mm-dd",
                orientation: "bottom",
                todayHighlight: !0,
                autoclose: !0,
                endDate: '-1d',
                language: 'fr'
            })
        } catch (e) {$.noop()}
    },
    handleDateTimePicker = function() {
        try{
            $("#datetimepicker3").datetimepicker({format: 'YYYY-MM-DD HH:mm', minDate: moment().subtract(0, 'days').millisecond(0).second(0).minute(0).hour(0)}), $("#datetimepicker4").datetimepicker({format: 'YYYY-MM-DD HH:mm', minDate: moment().subtract(0, 'days').millisecond(0).second(0).minute(0).hour(0)}), $("#datetimepicker3").on("dp.change", function(e) {
                $("#datetimepicker4").data("DateTimePicker").locale('fr').minDate(moment().subtract(0, 'days').millisecond(0).second(0).minute(0).hour(0))
            }), $("#datetimepicker4").on("dp.change", function(e) {
                $("#datetimepicker3").data("DateTimePicker").locale('fr').minDate(moment().subtract(0, 'days').millisecond(0).second(0).minute(0).hour(0))
            })

            $("#datetimepicker-modal-1").datetimepicker({format: 'YYYY-MM-DD HH:mm', minDate: moment().subtract(0, 'days').millisecond(0).second(0).minute(0).hour(0)}), $("#datetimepicker-modal-2").datetimepicker({format: 'YYYY-MM-DD HH:mm', minDate: moment().subtract(0, 'days').millisecond(0).second(0).minute(0).hour(0)}), $("#datetimepicker-modal-1").on("dp.change", function(e) {
                $("#datetimepicker-modal-2").data("DateTimePicker").locale('fr').minDate(moment().subtract(0, 'days').millisecond(0).second(0).minute(0).hour(0))
            }), $("#datetimepicker-modal-2").on("dp.change", function(e) {
                $("#datetimepicker-modal-1").data("DateTimePicker").locale('fr').minDate(moment().subtract(0, 'days').millisecond(0).second(0).minute(0).hour(0))
            })
        } catch (e) {$.noop()}
    },
    FormPlugins = function() {
        "use strict";
        return {
            init: function() {
                handleBootstrapCombobox(), handleSelect2(), handleFormPasswordIndicator(), handleDatepicker(), handleDateTimePicker()
            }
        }
    }();