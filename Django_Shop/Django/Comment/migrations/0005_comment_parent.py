# Generated by Django 3.2 on 2021-05-12 12:38

from django.db import migrations, models
import django.db.models.deletion


class Migration(migrations.Migration):

    dependencies = [
        ('Comment', '0004_auto_20210428_1911'),
    ]

    operations = [
        migrations.AddField(
            model_name='comment',
            name='parent',
            field=models.ForeignKey(null=True, on_delete=django.db.models.deletion.CASCADE, related_name='child', to='Comment.comment', verbose_name='والد'),
        ),
    ]
