<div class="form-group has-feedback" ng-class="{'has-success': form['{{name}}'].$valid && form['{{name}}'].$dirty && !form['{{name}}'].$error.required, 'has-error': form['{{name}}'].$dirty && form['{{name}}'].$invalid && !form['{{name}}'].$error.required, 'has-warning' : form['{{name}}'].$dirty && form['{{name}}'].$invalid && form['{{name}}'].$error.required}">
    <label for="{{id}}" class="control-label" ng-bind="label ? label : '{{name}}'" ng-class="{'sr-only' : !label}"></label>
    <input type="{{type}}" name="{{name}}" id="{{id}}" placeholder="{{placeholder}}" ng-model="ngModel" ng-model-options="{allowInvalid : true}" ng-minlength="{{ngMinlength}}" ng-maxlength="{{ngMaxlength}}" ng-required="required ? true : false" ng-readonly="readonly ? true : false" uib-popover-template="popover.templateUrl" popover-placement="bottom" popover-trigger="none" popover-is-open="thereAreErrors" ng-focus="triggerIfDirtyAndInvalid()" ng-change="triggerIfDirtyAndInvalid()" ng-blur="closeValidationPopover()"/>
    <span class="fa form-control-feedback" ng-class="{'fa-check': form['{{name}}'].$valid && form['{{name}}'].$dirty && !form['{{name}}'].$error.required, 'fa-times': form['{{name}}'].$dirty && form['{{name}}'].$invalid && !form['{{name}}'].$error.required, 'fa-exclamation-triangle' : form['{{name}}'].$dirty && form['{{name}}'].$invalid &&  form['{{name}}'].$error.required}"></span>
</div>