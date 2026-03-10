import { NextRequest } from "next/server";
import { handleLegacyRequest } from "@/legacy/handler";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

type Params = {
  params: Promise<{ path: string[] }>;
};

async function getPath(paramsPromise: Params["params"]): Promise<string[]> {
  const params = await paramsPromise;
  return params.path ?? [];
}

export async function GET(request: NextRequest, { params }: Params) {
  return handleLegacyRequest(request, await getPath(params));
}

export async function POST(request: NextRequest, { params }: Params) {
  return handleLegacyRequest(request, await getPath(params));
}

export async function PUT(request: NextRequest, { params }: Params) {
  return handleLegacyRequest(request, await getPath(params));
}

export async function DELETE(request: NextRequest, { params }: Params) {
  return handleLegacyRequest(request, await getPath(params));
}

export async function PATCH(request: NextRequest, { params }: Params) {
  return handleLegacyRequest(request, await getPath(params));
}

export async function OPTIONS(request: NextRequest, { params }: Params) {
  return handleLegacyRequest(request, await getPath(params));
}
