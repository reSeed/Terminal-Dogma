main.controller('courseController',['utilities','$scope','$server','$routeParams','uiCalendarConfig','$timeout','$route','$cookies','inform','$rootScope','cartService','$location',function(utilities,$scope,$server,$routeParams,uiCalendarConfig,$timeout,$route,$cookies,inform,$rootScope,cartService,$location){
    var self = this;
    
    /* CONFIG */
    
    $rootScope.ogImage = 'http://www.reseed.it/' + 'imgs/fb/' + $routeParams.courseID + ".png";
    
    self.username = $cookies.get('username') || null;
    $route.current.locals.username = self.username; // For modal and GridsterResizer
    
    self.courseID = $routeParams.courseID;
    
    $scope.events = [];
    $scope.eventSources = [{events: $scope.events, color: 'green'}];
    
    $scope.uiConfig = {
    		calendar: {
    			lang : "it",
    			displayEventTime : false,
    			minTime : '06:00:00',
    			maxTime : '21:00:00',
    			contentHeight : 'auto',
    			editable : false,
    			selectable : false,
    			unselectAuto : false,
    			header : {
    				left:   'title',
    				center: '',
    				right:  'prev,next'
    			}
    		}
    };
    
    var scrollbarsCreated = false;
    
    /* METHODS */
    
    self.goTo = function(url)
    {
    	$location.path(url);
    }
    
    self.addCourse = function (){
    	if(self.username == null)
    	{
    		inform.add('Non risulti essere iscritto a reSeed. <div>Per iscriverti clicca sul pulsante "<emph>REGISTRATI</emph>" in alto a destra!</div>',{type: 'danger', "html": true});
    		return;
    	}
    	
    	var courseData = {
				courseID: self.courseInfo.courseID,
				courseName: self.courseName,
				simulation: self.hasSimulation,
				price: self.courseInfo.price,
				simulationPrice: self.courseInfo.simulationPrice,
				payCourse: !self.subscribed,
				paySimulation: !self.subscribedToSimulation,
				courseAlreadyPaid: self.subscribed,
				simulationAlreadyPaid: self.subscribedToSimulation
			};
    	
    	var result = cartService.addCourse(courseData);
    	
//    	console.log(result);
    	
    	if(result) inform.add('Hai aggiunto il corso di <emph>' + courseData.courseName + '</emph> al carrello.<div><a class="leaf" href="/#cart">Vai al carrello!</a></div>', {"html": true});;
    }
    
    self.isInCart = function() {
    	return cartService.isInCart(self.courseID);
    }
    
    self.isSimulationInCart = function() {
    	return cartService.isSimulationInCart(self.courseID);
    }
    
    self.addSimulation = function() {
    	
    	var courseItem = cartService.getItem(self.courseID);
    	
    	if(courseItem != null)
    	{
    		cartService.toggle(courseItem);
    	}
    	else 
    	{
    		var courseData = {
    				courseID: self.courseInfo.courseID,
    				courseName: self.courseName,
    				price: self.courseInfo.price,
    				simulation: self.hasSimulation,
    				payCourse: !self.subscribed,
    				paySimulation: !self.subscribedToSimulation,
    				simulationPrice: self.courseInfo.simulationPrice,
    				courseAlreadyPaid: self.subscribed,
    				simulationAlreadyPaid: self.subscribedToSimulation
    			};
    		
    		cartService.addCourse(courseData);
    	}
    	
    	inform.add('Hai aggiunto la simulazione di <emph>' + self.courseName + '</emph> al carrello.<div><a class="leaf" href="/#cart">Vai al carrello!</a></div>', {"html": true});
    }
    
    $scope.changeView = function(viewName){
        uiCalendarConfig.calendars['register'].fullCalendar('changeView',viewName);
    };
    
    /* Finds index of object by key */
    self.indexOfByKey = function(key, value, array){
        for(var i=0; i<array.length;i++){
            if(array[i][key] === value) return i;
        }
        return -1;
    };
    
    /* Gives structure to db via informations retrieved by CodeIgniter */
    self.buildDB = function()
    {
        self.db = self.lessons;
        
        self.firstLesson = self.db.length > 0 ? moment(self.db[Object.keys(self.db)[0]].startingDate).format("dddd D MMMM") : null;
        
        if(!$scope.events) $scope.events = [];
        $scope.events.splice(0,$scope.events.length);
        
        angular.forEach(self.db,function(i){
            
            i.startingDate = moment(i.startingDate);
            i.endingDate = moment(i.endingDate);
            var newLesson = {lessonID: i.lessonID, title: i.courseID, start: i.startingDate, end: i.endingDate, note: i.lessonNote, stick: true};
            if(self.indexOfByKey('lessonID',i.lessonID,$scope.events) < 0) $scope.events.push(newLesson);
        });
    };
    
    self.items = [
//          {
//        	  id : 'subscription',
//	          title: 'Iscriviti al corso!',
//	          classes: 'bg-water button-responsive',
//	          textColor: 'white',
//	          width: 100,
//              noMaxHeight: true,
//              visible: imOnResponsive        	  
//          },
          {
        	  id : 'courseDescription',
	          title: self.courseName,
	          templateUrl: 'templates/course-description.php',
	          width: imOnResponsive ? 100 : 60,
              noMaxHeight: true,
              visible: true
          },
          {
              id : 'courseInfo',
              title: 'Informazioni',
              templateUrl: 'templates/course-info.php',
              width: imOnResponsive ? 100 : 39,
              offset: imOnResponsive ? 0 : 1,
              noMaxHeight: true,
              visible: true
          },
//          {
//        	  id : 'faq',
//	          title: 'Domande Frequenti',
//	          classes: 'bg-water button-responsive',
//	          width: 100,
//	          textColor: 'white',
//              noMaxHeight: true,
//              visible: imOnResponsive        	  
//          },
//          {
//              id : 'courseTeacher',
//              title: 'Docente',
//              bgColour: 'bg-light-lawn',
//              templateUrl: 'templates/course-teacher.php',
//              width: 100,
//          },
////          {
////        	  id: 'courseBanner',
////        	  templateUrl: 'templates/course-banner.php',
////              width: 100
////          },
          {
        	  id: 'whatYouLearn',
        	  title: 'Cosa imparerai',
        	  templateUrl: 'templates/course-whatyoulearn.php',
        	  width: 100,
        	  visible : true
          },
          {
              id : 'calendar',
              title: 'Calendario delle lezioni',
              templateUrl: 'templates/calendar.php',
              width: imOnResponsive ? 100 : 60,
              noMaxHeight: true,
              visible : self.subscribed
          },
          {
              id : 'courseNotifications',
              title: 'Avvisi',
              templateUrl: 'templates/course-notifications.php',
              width: imOnResponsive ? 100 : 39,
              offset: imOnResponsive ? 0 : 1,
              noMaxHeight: true,
              visible : self.subscribed  
          },
          {
              id : 'courseMaterials',
              title: 'Materiale del corso',
              bgColour: 'bg-light-water',
              templateUrl: 'templates/course-materials.php',
              width: 100,
              visible : self.subscribed        	  
          }
	];
    
    self.getItemClass = function(item)
    {
    	return 'col-' + item.width + (item.offset ? ' offset-'+item.offset : '') + (!item.classes ? ' bg-light-grey' : '') ;
    };
    
    self.isNewRow = function(index)
    {
    	var sum = 0;
    	for(var i=0; i<index; i++)
    	{
    		sum += self.items[i].width + (self.items[i].offset ? self.items[i].offset : 0);
    	}
    	if(sum%100 === 0) return true;
    	else return false;
    }
    
    self.getAdjacent = function(id){
    	if(id==='courseDescription') return 'courseInfo';
    	if(id==='courseInfo') return 'courseDescription';
    	if(id==='courseNotifications') return 'calendar';
    	if(id==='calendar') return 'courseNotifications';
    	else return id;
    };
    
     // MAIN
    
    self.courseInfoAjax = $server.post('courses/get',{courseID : self.courseID}).then(function(response) {
    	
    	self.courseInfo = response.data;
//    	console.log(self.courseInfo);
    	
    	self.courseInfo.startingDateText = moment(response.data.startingDate).format("D MMMM YYYY");
    	self.courseInfo.endingDateText = moment(response.data.endingDate).format("D MMMM YYYY");
    	self.courseHasStarted = moment().isAfter(moment(response.data.startingDate));
    	self.next = response.data.next ? response.data.next[0] : null;
    	self.hourPrice = Math.round(100 * self.courseInfo.price/self.courseInfo.duration)/100;
    	self.courseInfo.lessons = self.courseInfo.duration / 4;
    	self.courseInfo.day = moment(response.data.startingDate).format("dddd").toLowerCase();
    	self.courseInfo.startingHour = moment(response.data.startingHour).format("HH:mm");
    	self.courseInfo.endingHour = moment(response.data.endingHour).format("HH:mm");
    	
    	if(response.data.simulationStartingDate)
    	{
    		self.hasSimulation = true;
	    	self.courseInfo.simulationStartingDateText = moment(response.data.simulationStartingDate).format("D MMMM YYYY");
	    	self.courseInfo.simulationEndingDateText = moment(response.data.simulationEndingDate).format("D MMMM YYYY");
	    	self.simulationHasStarted = moment().isAfter(moment(response.data.simulationStartingDate));
	    	self.simulationHourPrice = Math.round(100 * self.courseInfo.simulationPrice/self.courseInfo.simulationDuration)/100;
	    	self.courseInfo.simulationLessons = self.courseInfo.simulationDuration / 2;
	    	self.courseInfo.simulationDay = moment(response.data.simulationStartingDate).format("dddd").toLowerCase();
	    	self.courseInfo.simulationStartingHour = moment(response.data.simulationStartingHour).format("HH:mm");
	    	self.courseInfo.simulationEndingHour = moment(response.data.simulationEndingHour).format("HH:mm");
    	}
    	
		self.courseName = response.data.name;
		
		$rootScope.title = self.courseName + " - reSeed";
		$rootScope.description = response.data.shortDescription;
		
		$rootScope.ogTitle = "Corso di " + $rootScope.title;
	    $rootScope.ogDescription = $rootScope.description;
	    if(self.courseName == "Rendering per architetti")
	    {
	    	$rootScope.ogTitle = "Corso di rendering fotorealistico";
	    	$rootScope.ogDescription = "Rendering fotorealistici in 3D Studio Max e V-Ray";
	    }
	    
//		switch (self.courseName) {
//		case 'Java':
//			$rootScope.description = 'Impara il linguaggio di programmazione che controlla più di 4 miliardi di dispositivi!';
//			break;
//		case 'Mobile App':
//			$rootScope.description = 'Apprendi le basi per sviluppare un\'applicazione Android!';
//			break;
//		case 'HTML + CSS + JS':
//			$rootScope.description = 'Un corso introduttivo ma completo sulle nozioni e funzionalità di base della programmazione web: HTML, CSS e Javascript';
//			break;
//		case 'MVC Development':
//			$rootScope.description = 'Un corso completo sui framework più diffusi e potenti (AngularJS, jQuery e CodeIgniter) per lo sviluppo di applicazioni web.';
//			break;
//		case 'Game Design':
//			$rootScope.description = 'Fondamenti di game design e progettazione di un videogioco da realizzare in collaborazione con altri corsi.';
//			break;
//		case 'Game Maker':
//			$rootScope.description = 'Come creare un videogioco da zero utilizzando Game Maker Studio, e suo effettivo sviluppo in collaborazione con altri corsi.';
//			break;
//		case 'Games for Dummies':
//			$rootScope.description = 'Corso per principianti mirato allo sviluppo di un videogioco senza alcuna conoscenza di linguaggi di programmazione.';
//			break;
//		case '3D Studio Max':
//			$rootScope.description = 'Modellazione, texturing, illuminazione e rendering in 3DS Max + V-Ray e realizzazione della grafica per un videogioco in collaborazione con altri corsi.';
//			break;
//			
//		default:
//			break;
//		}
		
    	for(var i=0; i< self.items.length; i++)
    	{
    		if(self.items[i].id === 'courseDescription')
    		{
    			self.items[i].title = self.courseName;
    		}
    	}
    });
    
//    self.teacherAjax = $server.post('teachers/get',{courseID : self.courseID}).then(
//		function(response)
//		{
//			self.teacher = response.data;
//		}
//    );
    
    self.notificationsAjax = $server.post('notifications/get',{courseID : self.courseID}).then(function(response) {
    	 self.notifications = response.data;
    });
    
    self.materialsAjax = $server.post('course_material/get_all',{courseID : self.courseID}, false).then(function(response) {
        var data = response.data;
        
        if(!response.data.error)
        {
	        angular.forEach(data,function(m){
	            m.getFA = function(){
	                var fileExtension = m.fileURI.split('.');
	                fileExtension = fileExtension[fileExtension.length-1];
	                if(fileExtension === 'jpg' || fileExtension === 'jpeg' || fileExtension === 'png' || fileExtension === 'gif') fileExtension = 'image';
	                if(fileExtension === 'doc' || fileExtension === 'docx') fileExtension = 'word';
	                if(fileExtension === 'ppt' || fileExtension === 'pptx') fileExtension = 'powerpoint';
	                if(fileExtension === 'xls' || fileExtension === 'xlsx') fileExtension = 'excel';
	                if(fileExtension === 'rar') fileExtension = 'zip';
	                if(fileExtension === 'c' || fileExtension === 'java' || fileExtension === 'php' || fileExtension === 'js' || fileExtension === 'html') fileExtension = 'code';
	                return 'fa-file-' + fileExtension + '-o';
	            };
	            m.getTitle = function(){
	                var title = m.fileURI.split('/');
	                title = title[title.length-1].split('.');
	                title = title[0];
	                title = title.replace(/_/g,' ');
	                return title;
	            };
	        });
        
	        self.materials = data;
        }
        else self.materials = [];
    });
    
    self.lessonsAjax = $server.post('lessons/get',{courseID: self.courseID}).then(function(response){
     	self.lessons = response.data;
        
        self.buildDB();
    });
    
    self.coursesAjax = $server.post('payment_interface/get_courses',{username: self.username}).then(
		function(response)
		{
			self.tempCourses = response.data;
			
		    for(var i=0; i<self.tempCourses.length; i++)
		    {
			   	if(self.tempCourses[i].courseID === self.courseID)
			   	{
			   		self.subscribed = parseInt(self.tempCourses[i].course);
			   		self.subscribedToSimulation = parseInt(self.tempCourses[i].simulation);
			   		
			   		self.presubscribed = true;
			   		
//			   		console.log(self);
			   	}
		    }
		}
	);
    
    $scope.$watchCollection(
    		function()
    		{
    			var ajaxes = [self.coursesAjax, self.courseInfoAjax, self.lessonsAjax, self.notificationsAjax, self.materialsAjax];
    			var states = [];
    			var allDefined = true;
    			for(var i=0; i<ajaxes.length;i++)
    			{
    				if(!ajaxes[i].$$state) allDefined = false;
    				else states[i] = ajaxes[i].$$state.status;
    			}
    			return states;
    		},
    		function(newValues){
    			
    			var allReady = true;
    			
    			for(var i = 0; i < newValues.length; i++)
    			{
    				if(!newValues[i]) allReady=false;
    			}
    			
    			if(allReady && !imOnResponsive)
    			{
    				$scope.$broadcast('allReady');
    				$timeout(function(){
    					
    					var timer;
    					$scope.$watchCollection(
    							function(){
    				    			if($('.no-gridster-item').length > 0)
    				    			{
    				    				var items = $('.no-gridster-item');
    				    				var heights = [];
    				    				items.each(function(){
    				    					heights.push($(this).height());
    				    				});
    				    				return heights;
    				    			}
    							},
    							function(){
    		    					
    								$timeout.cancel(timer);
    								timer = $timeout(function(){
    									
    									$('.no-gridster-item').each(function(){
        		    	    				$(this).find('.scrollbar-wrapper').height(
        		    	    						$(this).height() - $(this).find('.panel-title').height() - 20
        		    	    				);
        		    	    			});
        		    	    			
        		    	    			var scrollbars = $('.no-gridster-item').find('.scrollbar');
        		    	    			
        		    	    			if(!scrollbarsCreated)
        		    					{
        		    						scrollbars.perfectScrollbar({
        		    							suppressScrollX: true,
        		    							useSelectionScroll: true
        		    						});
        		    						scrollbarsCreated = true;
        		    					}
        		    					else
        		    					{
        		    						scrollbars.perfectScrollbar('update');
        		    					}        		    	    			
    								},250);
    								
    							}
    					);	
    				});
    			}
    		}
    );
    
    
    
}]);