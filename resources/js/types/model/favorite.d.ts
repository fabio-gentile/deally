export interface Favorite {
  // columns
  id: number
  created_at: string | null
  updated_at: string | null
  user_id: number
  favoritable_type: string
  favoritable_id: number
  // relations
  favoritable: Favorite
}
