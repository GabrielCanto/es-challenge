class FormFiller {

    static apply(data) {

        let key;

        for ( key in data ) {
            if ( document.getElementById(key) ) {
                document.getElementById(key).setAttribute('value', data[key]);
            }
        }

    }

}