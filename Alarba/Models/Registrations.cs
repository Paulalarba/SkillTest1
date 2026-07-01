using Microsoft.EntityFrameworkCore;
using Alarba.Models.Data;
using System.ComponentModel.DataAnnotations;
namespace Alarba.Models
{
    public class Registrations
    {
        [Key]
        public int Id { get; set; }
        [Display(Name = "Registration ID")]
        public int regId { get; set; }
        [Display(Name = "Registration Name")]
        public int RegistraionId { get; set; }
        [Display(Name = "Registration Date")]
        public string regDate { get; set; }
        [Display(Name = "Registration Fee")]
        public decimal RegistrationFee { get; set; }
        [Display(Name = "Payment Options")]
        public string regPayOptions { get; set; }

    }
}
