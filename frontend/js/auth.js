

document.addEventListener('DOMContentLoaded', 
    function() {

        const form = document.getElementById('login_form');

        form.addEventListener('submit',
            function( e ) {
                e.preventDefault();

                let username = document.getElementById('username').value;
                let password = document.getElementById('password').value;

                document.getElementById('login_msg').style.display = 'none';

                let api = new EmployeeApi();

                api.doLogin(username, password).then (
                    function( data ) {
                        document.location.href = '/frontend/employee_edit.html';
                    }
                )
                .catch( 
                    function(data) {
                        document.getElementById('login_msg').style.display = 'block';
                        document.getElementById('login_msg').innerHTML = 'Invalid Credentials';
                    }
                )
            }
        );

    }
);


