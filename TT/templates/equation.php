<div id="TT-equation">
	
	<div math ng-repeat="step in equation.e.solution.steps">
		<h6 ng-bind-html="step.description"></h6>
		<h5 ng-bind="step.formula.display()"></h5>
		<hr>
	</div>

</div>