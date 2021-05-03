import pandas as pd
import tensorflow as tf
import numpy as np 
from tensorflow.python.keras.models import Sequential
from tensorflow.python.keras.layers.core import Dense
from tensorflow import *;

#data = load data vector

def recommendByAI (data) :

    # create model type
    model = Sequential()

    # add main layers to neural network
    model.add(Dense(128, activation="relu"))
    model.add(Dense(128, activation="relu"))
    model.add(Dense(128, activation="relu"))

    # final layer is dense 5 as this is the amount of classes (ratings 1 to 5 stars)
    model.add(Dense(10, activation="softmax"))

    # compile 
    model.compile(optimizer="adam", loss="categorical_crossentropy")

    # fit model
    model.fit(x=data, batch_size=20, epochs=10)



recommendByAI()