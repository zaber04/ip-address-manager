# Use an official Node runtime as a parent image
FROM node:20-alpine

# Set the working directory to /app
RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app


# Copy package.json and package-lock.json to the working directory
COPY package*.json ./

# Install Angular CLI
RUN npm install -g @angular/cli

# Install app dependencies
RUN npm install

# Copy the current directory contents into the container at /app
COPY . .

# Build the application
RUN npm run build --prod

# Make port 4200 available to the world outside this container
EXPOSE 4200

# Define the command to run your app using the Angular CLI
CMD ["ng", "serve", "--host", "0.0.0.0", "--disable-host-check"]
