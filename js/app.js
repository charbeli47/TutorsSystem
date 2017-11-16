
// replace these values with those generated in your TokBox Account
var apiKey = "45989702";
var sessionId = "2_MX40NTk4OTcwMn5-MTUwOTEyMzkyNDg4MH5nbENyMzdwZFVRbDVYQ2ErQUI3WGY4ajZ-fg";
var token = "T1==cGFydG5lcl9pZD00NTk4OTcwMiZzaWc9NzM5NTIzMmRkMzliMDVhYjU3ODg0MTU5NzQxNWY3NTg3MDQwYmQxYTpzZXNzaW9uX2lkPTJfTVg0ME5UazRPVGN3TW41LU1UVXdPVEV5TXpreU5EZzRNSDVuYkVOeU16ZHdaRlZSYkRWWVEyRXJRVUkzV0dZNGFqWi1mZyZjcmVhdGVfdGltZT0xNTA5MTIzOTU3Jm5vbmNlPTAuMzM4OTU2NDY1MTc2MDU4NiZyb2xlPXB1Ymxpc2hlciZleHBpcmVfdGltZT0xNTExNjcwMzk5JmluaXRpYWxfbGF5b3V0X2NsYXNzX2xpc3Q9";

initializeSession();
// Handling all of our errors here by alerting them
function handleError(error) {
    if (error) {
        alert(error.message);
    }
}

function initializeSession() {
    var session = OT.initSession(apiKey, sessionId);

    // Subscribe to a newly created stream
    session.on('streamCreated', function (event) {
        session.subscribe(event.stream, 'subscriber', {
            insertMode: 'append',
            width: '100%',
            height: '100%'
        }, handleError);
    });

    // Create a publisher
    var publisher = OT.initPublisher('publisher', {
        insertMode: 'append',
        width: '100%',
        height: '100%'
    }, handleError);

    // Connect to the session
    session.connect(token, function (error) {
        // If the connection is successful, publish to the session
        if (error) {
            handleError(error);
        } else {
            session.publish(publisher, handleError);
        }
    });
}