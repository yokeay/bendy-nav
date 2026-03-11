const net = require("node:net");

function isPortAvailable(candidatePort) {
  return new Promise((resolve) => {
    const tester = net.createServer();
    tester.once("error", () => resolve(false));
    tester.once("listening", () => {
      tester.close(() => resolve(true));
    });
    tester.listen(candidatePort);
  });
}

function getRandomPort() {
  return new Promise((resolve) => {
    const server = net.createServer();
    server.once("error", () => resolve(0));
    server.listen(0, () => {
      const address = server.address();
      const port = typeof address === "object" && address ? address.port : 0;
      server.close(() => resolve(port || 0));
    });
  });
}

async function resolvePort(preferredPort, maxAttempts = 10) {
  if (Number.isFinite(preferredPort) && preferredPort > 0) {
    if (await isPortAvailable(preferredPort)) {
      return { port: preferredPort, reason: "preferred" };
    }
  }

  for (let attempt = 0; attempt < maxAttempts; attempt += 1) {
    const randomPort = await getRandomPort();
    if (randomPort && await isPortAvailable(randomPort)) {
      const reason = preferredPort ? "occupied" : "unconfigured";
      return { port: randomPort, reason };
    }
  }

  return { port: preferredPort || 3000, reason: "fallback" };
}

module.exports = {
  resolvePort
};
