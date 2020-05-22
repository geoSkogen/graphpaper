var Schema = require('./schema.js')
//expects /../records/faq-you.csv to be formatted as extra line-break separated Q & A, e.g.
/*
What is virtue?

I don't know what you mean.

What is being?

I haven't the first idea.

*/
var this_schema = new Schema('faq-you', '../records')
var new_array = []
var new_str = ''
var jsonData = {
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": []
}
var faqObj = {
  "@type": "Question",
  "name": "",
  "acceptedAnswer": {
    "@type": "Answer",
    "text": ""
  }
}
var this_faq = faqObj
var even = true
var even_even = false
this_schema.data.index.forEach(
   function (e) {
    if (even) {
      if (even_even) {
        e.forEach( function (ee) {
          this_faq.acceptedAnswer.text += ee
        })
        new_array.push(this_faq)
        this_faq = {
          "@type": "Question",
          "name": "",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": ""
          }
        }
      } else {
        e.forEach( function (eee) {
          this_faq.name += eee
        })
      }
      even_even = !even_even
    }
  even = !even
  }
)
jsonData.mainEntity = new_array
new_str = JSON.stringify(jsonData)
this_schema.export_csv(new_str, 'myjson', 'exports')
