const address = 'ShWXjqeNbm7Cd7onwC6eXT6e3jnejzZdDb';

// WAValidator is exposed as a global (window.WAValidator)
const valid = window.WAValidator.validate(address, currency = 'smartholdem', networkType = 'prod');
if(valid)
    console.log('This is a valid address');
else
    console.log('Address INVALID');