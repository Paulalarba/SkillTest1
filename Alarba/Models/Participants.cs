using Microsoft.EntityFrameworkCore;
using Alarba.Models.Data;
using System.ComponentModel.DataAnnotations;
namespace Alarba.Models
{
    public class Participants
    {
        [Key]
        public int Id { get; set; }
        public int partId { get; set; }

        public int evCode { get; set; }
        public string lastName { get; set; }
        public string firstname { get; set; }
        public decimal Discount { get; set; }
    }
}
