const { spawn } = require("node:child_process");
const path = require("node:path");
const { loadConfig, getConfiguredPort } = require("./runtime-config");
const { resolvePort } = require("./runtime-port");

const { config, configPath } = loadConfig();
const preferredPort = getConfiguredPort(config);

(async () => {
  const { port, reason } = await resolvePort(preferredPort);

  if (reason === "occupied") {
    console.warn(`Port ${preferredPort} is in use, switching to ${port}.`);
  } else if (reason === "unconfigured") {
    console.warn(`No port configured in ${configPath}, using random port ${port}.`);
  } else if (reason === "fallback") {
    console.warn(`Failed to find a random port, falling back to ${port}.`);
  }

  const nextBin = process.platform === "win32"
    ? path.join(process.cwd(), "node_modules", ".bin", "next.cmd")
    : path.join(process.cwd(), "node_modules", ".bin", "next");

  const child = spawn(nextBin, ["dev", "-p", String(port)], {
    stdio: "inherit",
    shell: process.platform === "win32"
  });

  child.on("exit", (code) => {
    process.exit(code ?? 0);
  });
})();
