// load-env.js
function loadEnvFile() {
    return fetch('.env')
      .then(response => response.text())
      .then(text => {
        const env = {};
        text.split('\n').forEach(line => {
          const [key, value] = line.split('=');
          env[key.trim()] = value.trim();
        });
        return env;
      });
  }
  
  // Usage example
  loadEnvFile().then(env => {
    console.log(env.PASSWORD);
  });