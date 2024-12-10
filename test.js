 import { GoogleGenerativeAI } from "@google/generative-ai";

const genAI = new GoogleGenerativeAI("AIzaSyBUyoWWZrC2zlgDMs7TVgY8eunmohTV8Kc");
const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

const prompt = "what's game of thrones"
const result = await model.generateContent(prompt)
console.log(result.response.text() )