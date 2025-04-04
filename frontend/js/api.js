class EmployeeApi {

    requireLogin() {

        let authRequest = this.doRequest('auth', { 'req': 'requireLogin' } );

        authRequest.catch (
            function( data ) {
                document.location.href = '/frontend/login.html';
            }
        )

        return authRequest;
    }

    doLogin( username, password ) {

        let req = this.doRequest('auth', 
            { 'req': 'doLogin', 'username': username, 'password': password }
        );

        return req;

    }

    getData(id) {

        return this.doRequest('employee', { id: id } );
    }

    doRequest( obj_type, params ) {

        let param;
        let param_string = '';

        for( param in params ) {
            param_string = param_string + param + '=' + params[param] + '&';
        }

        let request = new XMLHttpRequest();

        return new Promise( 
            function(resolve, reject) {
                request.onreadystatechange = function() {

                    // Only run if the request is complete
                    if (request.readyState !== 4) return;
                    
                    if (request.status >= 200 && request.status < 300) {
                        // If successful
                        let ret = JSON.parse(request.responseText);

                        if ( typeof(ret.success) != 'undefined' ) {
                            if ( ret.success != true ) {
                                reject({
                                    status: request.status,
                                    statusText: request.statusText
                                })
                            }
                        }
                        
                        console.log('responsetext', request.responseText);
				        resolve(ret);
                    } 
                    else {
                        reject({
                            status: request.status,
                            statusText: request.statusText
                        });
                        
                    }
                }

                request.open("GET", "/api.php?obj=" + obj_type + '&' + param_string, true);
                request.send();
            }
        );
        

    }
}