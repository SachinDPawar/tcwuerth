(function (moment) {
  'use strict';
  var moduleName = "ngMaterialDatePicker";

  var VIEW_STATES = {
    DATE: 0,
    HOUR: 1,
    MINUTE: 2
  };

  var css = function (el, name) {
    if ('jQuery' in window) {
      return jQuery(el).css(name);
    } else {
      el = angular.element(el);
      return ('getComputedStyle' in window) ? window.getComputedStyle(el[0])[name] : el.css(name);
    }
  };

  var template = '<md-dialog class="dtp" layout="column" style="width: 300px;">'
    +'  <md-toolbar><div class="md-toolbar-tools dtp-header">'
    +'   <md-button class="md-icon-button" aria-label="extended datetime picker">'
    +'        <md-icon>event_note</md-icon>'
    +'      </md-button>'
    +'      <h2>{{picker.currentDate.format("dddd")}}, {{picker.currentDate.format("MMMM")}} {{picker.currentDate.format("DD")}} <br/>'
    +'      week# {{picker.currentDate.isoWeek()}} @ {{picker.currentNearest5Minute().format(picker.params.shortTime ? "hh:mm" : "HH:mm")}}</h2>'
    +'     <md-button class="md-icon-button" ng-click="picker.hide()"><md-icon>close</md-icon><md-tooltip>Close</md-tooltip></md-button></div></md-toolbar>'
    +'   <md-divider></md-divider>'
    + '    <md-dialog-content class="dtp-content">'
    + '        <div class="dtp-date-view">'
    + '            <div class="dtp-date" ng-show="picker.params.date">'
    + '                <div layout="row">'
    + ' <div ng-click="picker.incrementYear(-1)" class="dtp-year-btn dtp-year-btn-prev" flex="30"><span ng-if="picker.isPreviousYearVisible()" >&#x25B2;</span></div>'
    + '                    <div class="dtp-actual-year" flex>{{picker.currentDate.format("YYYY")}}</div>'
    + ' <div ng-click="picker.incrementYear(1)" class="dtp-year-btn dtp-year-btn-next" flex="30"><span ng-if="picker.isNextYearVisible()" >&#x25BC;</span></div>'
    + '                </div>'
    + '            </div>'//start time
    + '            <div class="dtp-time" ng-show="picker.params.time && !picker.params.date">'
    + '                <div class="dtp-actual-maxtime">{{picker.currentNearest5Minute().format(picker.params.shortTime ? "hh:mm" : "HH:mm")}}</div>'
    + '            </div>'
    + '            <div class="dtp-picker">'
    + '                <mdc-datetime-picker-calendar date="picker.currentDate" picker="picker" class="dtp-picker-calendar" ng-show="picker.currentView === picker.VIEWS.DATE"></mdc-datetime-picker-calendar>'
    + '                <div class="dtp-picker-datetime" ng-show="picker.currentView !== picker.VIEWS.DATE">'
    + '                    <div class="dtp-actual-meridien">'
    + '                        <div class="left p20">'
    + '                            <a href="#" mdc-dtp-noclick class="dtp-meridien-am" ng-class="{selected: picker.meridien == \'AM\'}" ng-click="picker.selectAM()">{{picker.params.amText}}</a>'
    + '                        </div>'
    + '                        <div ng-show="!picker.timeMode" class="dtp-actual-time p60">{{picker.currentNearest5Minute().format(picker.params.shortTime ? "hh:mm" : "HH:mm")}}</div>'
    + '                        <div class="right p20">'
    + '                            <a href="#" mdc-dtp-noclick class="dtp-meridien-pm" ng-class="{selected: picker.meridien == \'PM\'}" ng-click="picker.selectPM()">{{picker.params.pmText}}</a>'
    + '                        </div>'
    + '                        <div class="clearfix"></div>'
    + '                    </div>'
    + '                    <mdc-datetime-picker-clock mode="hours" ng-if="picker.currentView === picker.VIEWS.HOUR"></mdc-datetime-picker-clock>'
    + '                    <mdc-datetime-picker-clock mode="minutes" ng-if="picker.currentView === picker.VIEWS.MINUTE"></mdc-datetime-picker-clock>'
    + '                </div>'
    + '            </div>'
    + '        </div>'
    + '    </md-dialog-content>'
    + '    <md-dialog-actions class="dtp-buttons" layout="row" layout-align="space-between end">'
    + '            <md-button class="dtp-btn-cancel md-icon-button" ng-click="picker.today()" ng-if="picker.currentView == picker.VIEWS.DATE">'
    + '             <md-tooltip md-direction="bottom"><em>Today: {{picker.todaysDate}} </em></md-tooltip>'
    + '             <md-icon>today</md-icon></md-button>'
    + '            <md-button class="dtp-btn-cancel md-button" ng-click="picker.cancel()"> {{picker.params.cancelText}}</md-button>'
    + '            <md-button class="dtp-btn-ok md-button" ng-click="picker.ok()"> {{picker.params.okText}}</md-button>'
    + '      </md-dialog-actions>'
    + '</md-dialog>';

  angular.module(moduleName, ['ngAnimate','ngMaterial'])
    .config(function($mdIconProvider) {
      $mdIconProvider.defaultIconSet('', 24);
    })
    .provider('mdcDatetimePickerDefaultLocale', function () {
      var language = (navigator.language || navigator.userLanguage).slice(0, 2);
      this.locale = language;

      this.$get = function () {
        return this.locale;
      };

      this.setDefaultLocale = function (localeString) {
        this.locale = localeString;
      };
    })
    .directive('mdcDatetimePicker', ['$mdDialog',
      function ($mdDialog) {

        return {
          restrict: 'A',
          require: 'ngModel',
          scope: {
            currentDate: '=ngModel',
            time: '=',
            date: '=',
            minDate: '=',
            maxDate: '=',
            disableDates: '=',
            weekDays: '=',
            shortTime: '=',
            format: '@',
            cancelText: '@',
            okText: '@',
            lang: '@',
            amText: '@',
            pmText: '@'
          },
          link: function (scope, element, attrs, ngModel) {
            var isOn = false;
            if (!scope.format) {
              if (scope.date && scope.time) {
                scope.format = 'YYYY-MM-DD HH:mm:ss';
              } else if (scope.date) {
                scope.format = 'YYYY-MM-DD';
              } else {
                scope.format = 'HH:mm';
              }
            }

            if (angular.isString(scope.currentDate) && scope.currentDate !== '') {
              scope.currentDate = moment(scope.currentDate, scope.format);
            }

            if (ngModel) {
              ngModel.$formatters.push(function (value) {
                if (typeof value === 'undefined') {
                  return;
                }
                var m = moment(value);
                return m.isValid() ? m.format(scope.format) : '';
              });
            }

            element.attr('readonly', '');
            //@TODO custom event to trigger input
            element.on('focus', function (e) {
              e.preventDefault();
              element.blur();
              if (isOn) {