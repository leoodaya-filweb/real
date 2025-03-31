// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
import { getDatabase } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-database.js";
// import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-analytics.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyATZvqKxl3gay1OwCgHQv_4oD4VHYM3AZQ",
  authDomain: "real-391007.firebaseapp.com",
  projectId: "real-391007",
  storageBucket: "real-391007.appspot.com",
  messagingSenderId: "899400151452",
  appId: "1:899400151452:web:8d3fcd068842ad5f945859",
  measurementId: "G-4V9L4D9RW0",
  databaseURL: 'https://real-391007-default-rtdb.asia-southeast1.firebasedatabase.app/'
};

// Initialize Firebase
const firebaseApp = initializeApp(firebaseConfig);
// const analytics = getAnalytics(app);
var database = getDatabase(firebaseApp);

export {
  database
}