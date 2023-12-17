# Use an official Node runtime as a parent image
# FROM node:14-alpine
FROM node:18.17.0-alpine

# Set the working directory to /app
# ENV HOME=/usr/src/app
# RUN mkdir -p $HOME
# WORKDIR $HOME
WORKDIR /app

# Copy package.json and package-lock.json to the working directory
COPY package*.json ./

# Install Angular CLI
RUN npm install -g @angular/cli

# Install app dependencies
RUN npm install

# Copy the current directory contents into the container at /app
COPY . .

# Make port 4200 available to the world outside this container
EXPOSE 4200

# Define the command to run your app using the Angular CLI
CMD ["ng", "serve", "--host", "0.0.0.0"]
