<div class="container">
    <div class="modal-wrapper">
        <div class="container">
            <h2>Modulo di registrazione</h2>
            <form id="signupForm" name="signupForm" ng-submit="signup.submit()" novalidate>
                <bootstrap-input form="signupForm" id="username" name="username" type="text" ng-model="signup.signupForm.username" placeholder="Username" ng-minlength="3" ng-maxlength="30" required="required"></bootstrap-input>
                <bootstrap-input form="signupForm" name="name" type="text" ng-model="signup.signupForm.name" placeholder="Nome" ng-minlength="2" ng-maxlength="30" required="required"></bootstrap-input>
                <bootstrap-input form="signupForm" name="surname" type="text" ng-model="signup.signupForm.surname" placeholder="Cognome" ng-minlength="2" ng-maxlength="30" required="required"></bootstrap-input>
                <bootstrap-input form="signupForm" name="mail" type="email" ng-model="signup.signupForm.mail" placeholder="Indirizzo e-mail" required="required"></bootstrap-input>
                <bootstrap-input form="signupForm" name="password" type="password" ng-model="signup.signupForm.password" placeholder="Digita la password che vuoi utilizzare" ng-minlength="8" required="required"></bootstrap-input>
                <bootstrap-input form="signupForm" id="repassword" name="repassword" type="password" ng-model="signup.signupForm.repassword" placeholder="Ridigita la password" required="required"></bootstrap-input>
				<div class="checkbox">
				   	<input type="checkbox" ng-model="signup.signupForm.disclaimerAccepted" ng-true-value="1" ng-false-value="0" required>
				    <label><p><span ng-bind-html="signup.disclaimerLink"></span></p></label>
				</div>
                <input class="modal-submit btn-success" type="submit" value="Iscriviti" />
                <input class="modal-cancel" ng-click="$dismiss()" value="Annulla" readonly/>
            </form>
        </div>
    </div>
</div>