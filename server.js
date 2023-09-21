const express = require("express");
const app = express();
const server = require("http").createServer(app);

const io = require("socket.io")(server, { cors: { origin: "*" } });

io.on("connection", (socket) => {
    console.log("connection");

    socket.on("messageEvent", (msg) => {
        console.log("message from client:", msg);
        socket.broadcast.emit("broadcast", msg);
    });

    socket.on("disconnect", (socket) => {
        console.log("Disconnect");
        // socket.disconnect();
    });
});

server.listen(3001, () => {
    console.log("server is running");
});
