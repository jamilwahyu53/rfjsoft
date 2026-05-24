const ApiService = {
    baseUrl: '/api',

    async get(endpoint) {
        $("#loader").show(); 
        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            return await response.json();
        } catch (error) {
            console.error('GET Error:', error);
            return { error: true, message: error.message };
        } finally{
            $("#loader").hide(); 
        }
    },

    async post(endpoint, data = {}) {
        $("#loader").show(); 
        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });
            return await response.json();
        } catch (error) {
            console.error('POST Error:', error);
            return { error: true, message: error.message };
        } finally {
            $("#loader").hide(); 
        }
    }
};
