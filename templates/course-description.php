<div class="container">
    <div class="course-description-introduction">
        <div class="container" id="course-description-introduction-container" size-on-scope>
            <div class="course-icon" ng-style="{'height':courseDescriptionIntroductionContainerHeight + 'px'}">
                <span ng-class="'icon-{{course.courseDescription.icon}}'" fittext></span>
            </div>
            <div class="course-description">
                <span ng-bind="course.courseDescription.description"></span>
            </div>
        </div>
    </div>
    <h5>Programma</h5>
    <div class="course-description-syllabus">
        <ul>
            <li ng-repeat="item in course.courseDescription.syllabus">
                <span ng-bind="item"></span>
            </li>
        </ul>
    </div>
</div>