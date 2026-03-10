import { NextRequest } from "next/server";
import { handleLegacyRequest } from "@/legacy/handler";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

export async function GET(request: NextRequest) {
  return handleLegacyRequest(request, []);
}

export async function POST(request: NextRequest) {
  return handleLegacyRequest(request, []);
}

export async function PUT(request: NextRequest) {
  return handleLegacyRequest(request, []);
}

export async function DELETE(request: NextRequest) {
  return handleLegacyRequest(request, []);
}

export async function PATCH(request: NextRequest) {
  return handleLegacyRequest(request, []);
}

export async function OPTIONS(request: NextRequest) {
  return handleLegacyRequest(request, []);
}
