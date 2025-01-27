# Chatbot Project

This is a simple chatbot project that combines Python, Flask, PHP, and MySQL. It uses a `CountVectorizer` model to generate responses based on user input provided in a CSV file. The project integrates a Flask API for backend processing and a PHP-based frontend for user interaction and chat management.

## Features

- User authentication system (login and registration).
- Create new chats and save chat history for each user.
- Chat interface styled similar to ChatGPT:
  - Left sidebar: Displays all saved chats.
  - Right section: Chatbox with typing effect for bot responses (one word at a time).
- Backend Flask API for chatbot processing.
- CountVectorizer model for generating responses based on input data.
- Update user profile picture.
- MySQL database for storing user data and chat history.

## Project Structure

### Backend
- **Python (Flask API)**:
  - Processes user input.
  - Communicates with the CountVectorizer model to generate responses.
  - Returns responses to the frontend via API.

- **CountVectorizer**:
  - Generates responses based on a CSV file containing input-output pairs.

### Frontend
- **PHP**:
  - User authentication (login/register).
  - Manages user profiles and chat creation.
  - Sends user input to the Flask API and displays chatbot responses.
  - Implements a typing effect for responses.
  
- **MySQL**:
  - Stores user data (e.g., username, email, profile picture).
  - Saves chat history for each user.

### Frontend Design
- Left sidebar:
  - Displays a list of all saved chats.
  - Allows switching between chats.
- Right chatbox:
  - Input box to send messages.
  - Displays bot responses with a typing effect (one word at a time).

## Installation

Follow these steps to set up and run the project:

### Prerequisites
- Python Libraries
    - sklearn (Scikit-Learn)
    - pandas
    - flask
    - flask_cors

### Setup

1. **Clone the repository**:
    ```bash
    git clone https://github.com/Nikulsuthar2/Chatbot-Flask-PHP-MySQL.git
    ```
2. Setup PHP Frontend:
    - Move the Chatbot-Flask-PHP-MySQL folder to your web server's root directory (e.g., htdocs for XAMPP).
    - Update the database credentials in `db.php` file in user folder:
    ```php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'chatbot';
    ```
    - Import the SQL file (chatbot.sql) from SQL folder into your MySQL server to create the database and tables.

3. **Setup Python Backend**:
    - Navigate to the `Flask API and Chatbot` directory:
    - Run the Flask API:
    ```bash
    python app.py
    ```
    - The API will start on http://127.0.0.1:5000.

4. Run the Project:

    - Start your web server and access the frontend at http://localhost/Chatbot-Flask-PHP-MySQL.

## Technology Stack
- **Backend**: PHP, Flask API, Scikit Learn
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Server**: XAMMP Apache (local server)
- **Chatbot**: Python CountVectorizer

## Usage
- Login or register a new account.
- Create a new chat or select an existing chat from the sidebar.
- Enter your message in the input box and send it.
- The chatbot will respond with a typing effect.
- Update your profile picture via the profile settings.

## Future Improvements
- Add natural language processing (NLP) for more accurate chatbot responses.
- Implement a payment gateway for potential premium features.
- Enhance the UI with modern CSS frameworks.
- Add multi-language support for the chatbot.

## Author
**Nikul Suthar** -> [Nikulsuthar2](https://github.com/Nikulsuthar2)

## Screenshots

![index]()

![index]()

![index]()

![index]()

![index]()

![index]()