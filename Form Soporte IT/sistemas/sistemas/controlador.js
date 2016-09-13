app.controller('showPass', ['$scope', function( $scope ){

					// Set the default value of inputType
					$scope.inputType = 'password';

					// Hide & show password function
					$scope.hideShowPassword = function(){
						if ($scope.inputType == 'password')
				 			$scope.inputType = 'text';
						else
				 			$scope.inputType = 'password';
					};
}]);

/*
// create angular controller
app.controller('validationController', function($scope) {

    // function to submit the form after all validation has occurred            
    $scope.submitForm = function(isValid) {

        // check to make sure the form is completely valid
        if (isValid) { 
            alert('lalallalalalalalalalal.....');
        }

    };

});

*/

app.controller('ContactController', function ($scope, $http) {
    $scope.result = 'hidden'
    $scope.resultMessage;
    $scope.formData; //formData is an object holding the name, email, subject, and message
    $scope.submitButtonDisabled = false;
    $scope.submitted = false; //used so that form errors are shown only after the form has been submitted
    $scope.submit = function(contactform) {
        $scope.submitted = true;
        $scope.submitButtonDisabled = true;
        if (contactform.$valid) {
            $http({
                method  : 'POST',
                url     : 'mail.php',
                data    : $.param($scope.formData),  //param method from jQuery
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  //set the headers so angular passing info as form data (not request payload)
            }).success(function(data){
                console.log(data);
                if (data.success) { //success comes from the return json object
                    $scope.submitButtonDisabled = true;
                    $scope.resultMessage = data.message;
                    $scope.result='bg-success';
                } else {
                    $scope.submitButtonDisabled = false;
                    $scope.resultMessage = data.message;
                    $scope.result='bg-danger';
                }
            });
        } else {
            $scope.submitButtonDisabled = false;
            $scope.resultMessage = 'Falla : Por favor complete todos los campos!.';
            $scope.result='bg-danger';
        }
    }
});

