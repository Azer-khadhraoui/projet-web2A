const express = require('express');
const https = require('https');
const app = express();
const port = 3000;

// Middleware to parse incoming JSON requests
app.use(express.json());

// Route to handle AI generation
app.post('/generate', (req, res) => {
    const { category } = req.body;

    // Dynamically set the prompt based on the selected category
    let prompt;
    if (category === 'potatoes') {
        prompt = 'suggest the best method for planting potatoes.';
    } else if (category === 'tomatoes') {
        prompt = 'suggest the best method for planting tomatoes.';
    } else {
        prompt = `suggest the best method for planting ${category}.`;
    }

    // Prepare the data for the API request
    const data = JSON.stringify({
        prompt: prompt,
        model: 'gemini-1.5-flash',
        apiKey: 'AIzaSyBUyoWWZrC2zlgDMs7TVgY8eunmohTV8Kc', // Replace with your actual API key AIzaSyDWGz_65GzZtmkfa22wq7whp5VP28sMMOY
    });

    // Set up the request options
    const options = {
        hostname: 'generative-ai.googleapis.com',
        port: 443,
        path: '/v1/models/gemini-1.5-flash:generateContent',
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Content-Length': Buffer.byteLength(data),
        },
    };

    // Send the request to the Google Generative AI API
    const apiRequest = https.request(options, (apiResponse) => {
        let responseData = '';

        // Collect the response data
        apiResponse.on('data', (chunk) => {
            responseData += chunk;
        });
        
        apiResponse.on('end', () => {
            console.log('Response Headers:', apiResponse.headers);
            console.log('Raw Response:', responseData); // Log the raw response
            res.send(responseData);
            try {
                const jsonResponse = JSON.parse(responseData); // This is where the error occurs if the response is HTML
                console.log('Parsed Response:', jsonResponse);
        
                if (jsonResponse.text) {
                    res.json({ text: jsonResponse.text });
                } else {
                    res.status(500).json({ error: 'Invalid response from AI: No text field found' });
                }
            } catch (error) {
                console.error('Error parsing response:', error);  // Log the error if parsing fails
                res.status(500).json({ error: 'Error parsing API response' });
            }
        });
        
        
    });

    // Handle request errors
    apiRequest.on('error', (error) => {
        console.error(error);
        res.status(500).json({ error: 'Error making API request' });
    });

    // Send the request data
    apiRequest.write(data);
    apiRequest.end();
});

// Start the server
app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
