<div id="navbar" ng-controller="navbarController as navbar">
    <div class="container">
        <div id="navbar-home">
            <div class="container">
                <div class="macrotab">
                    <a href="/"><span class="middler"></span>
                    <span class="fa-stack middle">
                    	<i class="fa fa-home fa-stack-2x"></i>
                    </span>
                    <span title="Torna alla home" class="middle">Home</span></a>
                </div>
            </div>
        </div>
        
        <div id="navbar-tabs">
            <div class="container">
                <div class="macrotab" equal-spans>
                    <a ng-repeat="tab in navbar.tabs" title="{{tab.title}}" equal-span ng-href="/{{tab.url}}"><span class="middle" ng-bind="tab.content"></span></a>
                </div>
            </div>
        </div>
        
        <div id="navbar-profile">
            <div class="container macrotab">
                <div id="navbar-signup" ng-if="!userVerified">
                    <div title="Registrati su reSeed.it" class="container">
                        <button class="btn btn-sm" ng-click="navbar.signup()">
                            <span>Registrati</span>
                        </button>
                    </div>
                </div>
                <div id="navbar-signin" ng-if="!userVerified">
                    <div title="Effettua il login su reSeed.it" class="container">
                        <button class="btn btn-round btn-sm" ng-click="navbar.signin()">
                            <span>Accedi</span>
                        </button>
                    </div>
                </div>
                
                <!-- If logged -->
                <div id="navbar-logged" ng-if="userVerified">
                    <div class="container">
                    	<div class="navbar-profile-name">
                    		<span class="noselect" fittext="0.9">
	                            <span class="middler"></span>
	                             <a title="Clicca per impostare un nuovo avatar" ng-mouseenter="hover = true" ng-mouseleave="hover = false" ng-click="avatar.changeAvatar(username,avatarURI)" ng-controller="avatarController as avatar">
	                            	<span class="fa-stack fa-sm clickable" ng-if="thereIsAvatar()">
	                            		<span class="fa-stack" >
		                            		<img class="avatar" ng-src="{{avatarURI}}">
		                            	</span>
	                            	</span>
	                            	<span class="fa-stack fa-sm clickable" ng-if="!thereIsAvatar()">
	                            		<i class="fa fa-question-circle fa-stack-2x" ng-class="{'light-olive':hover, 'white':!hover}"></i>
	                            	</span>
		                         </a>
		                         <a title="Accedi al tuo profilo" ng-href="/profile/{{username}}">
	                            	<span class="middle navbar-username" ng-bind="username"></span>
	                            </a>
	                            
	                            <span class="navbar-icons">
									<a title="Vedi le nuove notifiche" ng-href="/profile/{{username}}">
										<span class="fa-stack clickable" >
	                            			<i class="fa fa-bell-o fa-stack-2x"></i>
                            				<i class="fa-stack-1x number-of-notifications" ng-if="numberOfNotifications!==0"><span ng-bind="numberOfNotifications" ></span></i>
	                            		</span>
	                          		</a>
	                            	<a title="Imposta le preferenze del tuo profilo" ng-href="/preferences">
	                            		<span class="fa-stack clickable">
	                            			<i class="fa fa-cog fa-stack-2x"></i>
	                            		</span>
	                            	</a>
	                            	
	                            	<a title="Vai al carrello" ng-href="/cart" ng-class="{'cart-not-empty': cart.getCoursesToPay().length>0}">
	                            		<span class="fa-stack clickable">
	                            			<i class="fa fa-shopping-cart fa-stack-2x"></i>
	                            			<i class="fa-stack-1x number-of-cart-items" ng-if="cart.getCoursesToPay().length>0"><span ng-bind="cart.getCoursesToPay().length" ></span></i>
	                            		</span>
	                            	</a>
	                            	
	                            	<a title="Accedi al registro" ng-href="/register" ng-if="admin">
	                            		<span class="fa-stack clickable">
	                            			<i class="fa fa-calendar fa-stack-2x"></i>
	                            		</span>
	                            	</a>
	                        		<a title="Effettua il logout da reSeed" ng-click="navbar.signout()">
	                        			<span class="fa-stack clickable">
	                        				<i class="fa fa-sign-out fa-stack-2x"></i>
	                        			</span>
	                        		</a>
	                            </span>
                        	</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>