# Generated by Django 3.2 on 2021-04-27 15:29

from django.conf import settings
import django.core.validators
from django.db import migrations, models
import django.db.models.deletion


class Migration(migrations.Migration):

    dependencies = [
        ('Product', '0005_alter_product_slug'),
        migrations.swappable_dependency(settings.AUTH_USER_MODEL),
        ('Star', '0001_initial'),
    ]

    operations = [
        migrations.AlterField(
            model_name='star',
            name='product',
            field=models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, related_name='stars', to='Product.product', verbose_name='محصول'),
        ),
        migrations.AlterField(
            model_name='star',
            name='score',
            field=models.IntegerField(default=0, validators=[django.core.validators.MinValueValidator(0), django.core.validators.MaxValueValidator(5)], verbose_name='امتیاز'),
        ),
        migrations.AlterField(
            model_name='star',
            name='user',
            field=models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, related_name='stars', to=settings.AUTH_USER_MODEL, verbose_name='کاربر'),
        ),
    ]
