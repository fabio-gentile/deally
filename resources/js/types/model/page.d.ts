export interface Page {
  // columns
  id: number
  created_at: string | null
  updated_at: string | null
  title: string
  slug: string | null
  content: string
}
