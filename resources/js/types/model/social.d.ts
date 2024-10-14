export interface Social {
  // columns
  id: number
  created_at: string | null
  updated_at: string | null
  user_id: number | null
  provider: string | null
  provider_id: string | null
  provider_token: string | null
  provider_refresh_token: string | null
  // relations
  user: User
}
