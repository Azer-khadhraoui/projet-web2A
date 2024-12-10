import express from "express";
const app = express();
const port = 3000;
import { GoogleGenerativeAI } from "@google/generative-ai";

app.use(express.json());

app.post('/generate', async (req, res) => {
    const { category } = req.body;
    console.log("Received category:", category);

    try {
        // Initialize the Google Generative AI client
        const genAI = new GoogleGenerativeAI("AIzaSyADlYHKXNCv3cQyvO-KdbwtIvUhbfExJY4");
        const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

        // Prepare the prompt
        const prompt = "suggest me  the best product  method for planting " + category;

        // Generate AI content
        const result = await model.generateContent(prompt);

        // Log the response for debugging
        console.log("AI Response:", result);

        // Check if the response structure is correct
        if (result && result.response && result.response.text) {
            res.json({ text: result.response.text() });
        } else {
            // If the response doesn't have the expected structure
            console.error("Error: Invalid response from AI - No 'text' field found");
            res.status(500).json({ error: 'Invalid response from AI: No text field found' });
        }
    } catch (error) {
        // Catch any errors during the API call or response parsing
        console.error("Error:", error);
        res.status(500).json({ error: 'Error while generating content from AI', details: error.message });
    }
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
