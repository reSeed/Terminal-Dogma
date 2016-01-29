main.run(['$rootScope', function($rootScope) {
    $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
        $rootScope.title = current.$$route.title;
        $rootScope.description = current.$$route.description;
    });
}]);

main.config(['$routeProvider','$locationProvider',function($routeProvider,$locationProvider){
    
    $locationProvider.hashPrefix('!');
//    $locationProvider.html5Mode(true);
    
    // Home
    
    $routeProvider.when('/',{
    	description: 'Impara. Crea. Pubblica.',
        templateUrl : 'home'
    });
    
    // Admin Page - Restricted
    
    $routeProvider.when('/admin',{
    	title : 'Pannello Admin',
        templateUrl : 'admin'
    });
    
    // Payment
    
    $routeProvider.when('/payment',{
    	title : 'Pagamenti',
        templateUrl : 'payment'
    });
    
    // Payment-not-registered
    
    $routeProvider.when('/paymentNotRegistered',{
        templateUrl : 'paymentNotRegistered'
    });
    
    // Cancel_payment
    
    $routeProvider.when('/cancel_payment',{
        templateUrl : 'cancel_payment'
    });
    
    // Confirm_payment
    
    $routeProvider.when('/confirm_payment',{
        templateUrl : 'confirm_payment'
    });
    
    // Courses
    
    $routeProvider.when('/courses',{
    	title : 'Corsi',
    	description: 'BIM BUM BAM',
        templateUrl : 'courses'
    });
    
    // Activities
    
    $routeProvider.when('/activities',{
    	title : 'Servizi',
        templateUrl : 'activities'
    });
//    
//    // Media
//    
//    $routeProvider.when('/media',{
//        templateUrl : 'media'
//    });
    
    // Contacts
    
    $routeProvider.when('/contacts',{
    	title : 'Contatti',
        templateUrl : 'contacts'
    });
    
    // Disclaimer
    
    $routeProvider.when('/disclaimer',{
    	title : 'Termini d\'Uso',
        templateUrl : 'disclaimer'
    });
    
    // FAQ
    
    $routeProvider.when('/faq',{
    	title : 'FAQ',
        templateUrl : 'faq'
    });
    
    // Privacy
    
    $routeProvider.when('/privacy',{
    	title : 'Privacy Policy',
        templateUrl : 'privacy'
    });
    
    // Course
    
    $routeProvider.when('/courses/:courseID',{
        templateUrl : function(parameters){return 'course/index/'+parameters.courseID;},
        controller : 'courseController as course',
    });
    

    // Activity
    
    $routeProvider.when('/activities/:activityID',{
//    	title : function(parameters){return parameters.activityID},
        templateUrl : function(parameters){return 'activity/index/'+parameters.activityID;},
        controller : 'activityController as activity'
    });
    
    // Profile
    
    $routeProvider.when('/profile/:userID',{
//        templateUrl : 'profile',
    	title : 'Profilo',
    	templateUrl: function(parameters){return 'profile/index/'+parameters.userID;},
        controller : 'profileController as profile'
    });
    
    $routeProvider.when('/preferences',{
    	title: 'Pannello Utente',
    	templateUrl: 'preferences',
    	controller: 'preferencesController as preferences'
    });
    
    // Register - Restricted
    
    $routeProvider.when('/register',{
    	title : 'Registro Elettronico',
        templateUrl : 'register',
        controller : 'Register as register'
    });
    
    // Presentazione ai licei
    
    $routeProvider.when('/school',{
    	title : 'Presentazione',
        templateUrl : 'school'
    });
    
    $routeProvider.otherwise({
    	templateUrl: 'error'
    });
    
}]);