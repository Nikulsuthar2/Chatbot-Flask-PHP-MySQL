# -*- coding: utf-8 -*-
"""
Created on Fri Mar 24 18:08:18 2023

@author: nikul
"""
import os
import pandas as pd
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.metrics.pairwise import cosine_similarity

path = os.path.join(os.getcwd(),"Flask API and Chatbot","Data");

# Load the dataset into a pandas DataFrame
df = pd.read_csv(os.path.join(path,"response.csv"))
aidf = pd.read_csv(os.path.join(path,"airesponse.csv"))
fullformdf = pd.read_csv(os.path.join(path,"fullform.csv"))
defination = pd.read_csv(os.path.join(path,"defination.csv"))
htmlqna = pd.read_csv(os.path.join(path,"htmlqna.csv"))
mentalhealth = pd.read_csv(os.path.join(path,"Mental_Health_FAQ.csv"))
hindi = pd.read_csv(os.path.join(path,"hindiresponse.csv"))
pyprograms = pd.read_csv(os.path.join(path,"pythonprograms.csv"))

df = pd.concat([df,aidf,fullformdf,defination,htmlqna,hindi,mentalhealth,pyprograms])
# Create a CountVectorizer object to convert the text into a matrix of token counts
vectorizer = CountVectorizer()

# Fit the vectorizer on the input text
X = vectorizer.fit_transform(df['input'])

# Define a function to get the response to a given input
def get_response(input_text):
    # Preprocess the input text by converting it to lowercase and removing punctuation
    input_text = input_text.lower().strip()
    
    # Convert the preprocessed input text into a matrix of token counts
    input_matrix = vectorizer.transform([input_text])
    
    # Compute the cosine similarity between the input matrix and the dataset matrix
    similarity_scores = cosine_similarity(input_matrix, X)
    
    # Find the index of the most similar input in the dataset
    max_index = similarity_scores.argmax()
    
    # Return the corresponding response from the dataset
    return df.iloc[max_index]['output']

# Test the chatbot
# while True:
#    input_text = input('You: ')
#    if input_text == 'exit':
#        break
#    response = get_response(input_text)
#    response = response.replace("\\n",'\n')
#    response = response.replace("\\t",'\t')
#    print('Bot:', response)
