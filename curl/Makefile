BASE_URL=http://127.0.0.1:8000
API_BASE=$(BASE_URL)/api

ADMIN_EMAIL=admin@queue.test
ADMIN_PASSWORD=admin12345

TOKEN_JSON=./responses/access_token.json
AUTH_HEADER="Authorization: Bearer $(shell jq -r '.access_token' $(TOKEN_JSON) 2>/dev/null)"

.PHONY: login whoami issue-queue next-queue prev-queue list-queue-admin public-queue

# =========================
# AUTH
# =========================
login:
	@mkdir -p ./responses
	@curl -s "$(API_BASE)/admin/login" \
		-X POST \
		-H "Content-Type: application/json" \
		-H "Accept: application/json" \
		-d '{"email":"$(ADMIN_EMAIL)","password":"$(ADMIN_PASSWORD)"}' \
		| jq -r '{access_token: .token}' > $(TOKEN_JSON)
	@if [ ! -s $(TOKEN_JSON) ] || [ "$$(jq -r '.access_token // empty' $(TOKEN_JSON))" = "" ]; then \
		echo "Failed to login or token missing"; \
		exit 1; \
	fi
	@echo "Token saved to $(TOKEN_JSON)"

# Optional sanity check: see token value
whoami:
	@jq -r '.' $(TOKEN_JSON)

# =========================
# QUEUE (ADMIN)
# =========================

issue_queue:
	curl -s "$(API_BASE)/admin/queue/issue" \
		-X POST \
		-H $(AUTH_HEADER) \
		-H "Accept: application/json" \ 

next_queue:
	curl -s "$(API_BASE)/admin/queue/next" \
		-X POST \
		-H $(AUTH_HEADER) \
		-H "Accept: application/json" \
		

prev_queue:
	curl -s "$(API_BASE)/admin/queue/prev" \
		-X POST \
		-H $(AUTH_HEADER) \
		-H "Accept: application/json" \
		

list_queue_admin:
	curl -s "$(API_BASE)/admin/queues" \
		--request GET \
		-H $(AUTH_HEADER) \
		-H "Accept: application/json" | jq

# =========================
# QUEUE (PUBLIC)
# =========================
public_queue:
	curl -s "$(API_BASE)/queue/public" \
		--request GET \
		-H "Accept: application/json" \
		| jq

